<?php

namespace App\Http\Controllers\Report;

use App\Casts\MoneyCast;
use App\Http\Controllers\Controller;
use App\Models\CashFlow;
use App\Models\LabaRugi;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class EquityStatementController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            // Redirect to login if the user is not authenticated
            return redirect('/merchant')->with('error', 'Please login first.');
        }


        $year = request('tableFilters.year.value') ?? date('Y');

        $merchant = Auth::user()->teams()->first();
        if (!$merchant) {
            // Redirect to a specific path if no team is associated
            return redirect('/merchant');
        }

        $modal = $this->getEquityBalances($year, 'Equity', $merchant->id, '3-110');

        $equityMovement = $this->getAccountBalances($year, 'Equity', $merchant->id);
        $currentRevenue = $this->calculateRevenue($year, '=', $merchant->id);


        $equityMovement->transform(function ($item) use ($currentRevenue) {
            if ($item->account_code === '3-310') {
                $item->balance = $currentRevenue;
            }
            return $item;
        });

        $pdf = Pdf::loadView(
            'report.equity-statement',
            [
                'year' => $year,
                'merchant' => $merchant->name,
                'modal' => $modal,
                'equityMovement' => $equityMovement,
            ]
        )->setPaper('a4', 'potrait');


        // Return the PDF as a stream (download in the browser)
        return $pdf->stream('Laporan Perubahan Modal ' . $merchant->name . '_' . $year . '.pdf');

    }

    protected function getAccountBalances($year, $accountType, $team_id)
    {
        $balanceExpression = $accountType === 'Revenue'
            ? DB::raw("SUM(CASE WHEN cash_flows.type = 'debit' THEN cash_flows.amount ELSE 0 END) - SUM(CASE WHEN cash_flows.type = 'credit' THEN cash_flows.amount ELSE 0 END) as balance")
            : DB::raw("SUM(CASE WHEN cash_flows.type = 'credit' THEN cash_flows.amount ELSE 0 END) - SUM(CASE WHEN cash_flows.type = 'debit' THEN cash_flows.amount ELSE 0 END) as balance");

        // Query to get the accounts with the specified type and their balances for the given date range and team
        $accounts = DB::table('accounts')
            ->leftJoin('cash_flows', function ($join) use ($year, $team_id) {
                $join->on('accounts.id', '=', 'cash_flows.account_id')
                    ->where('cash_flows.team_id', '=', $team_id)
                    ->whereYear('cash_flows.transaction_date', '=', $year);
            })
            ->select(
                'accounts.code as account_code',
                'accounts.accountName as account_name',
                $balanceExpression
            )
            ->where('accounts.team_id', $team_id)
            ->where('accounts.accountType', $accountType) // Filter for accounts with type
            ->groupBy('accounts.id', 'accounts.code', 'accounts.accountName')
            ->get();

        // Apply the MoneyCast to balance for each account
        $accounts->transform(function ($account) {
            // Apply MoneyCast to the balance field
            $account->balance = (new MoneyCast())->get(null, 'balance', $account->balance, []);
            return $account;
        });

        return $accounts;
    }

    protected function getEquityBalances($year, $accountType, $team_id, $specificAccountCode = null)
    {
        $balanceExpression = $accountType === 'Revenue'
            ? DB::raw("SUM(CASE WHEN cash_flows.type = 'debit' THEN cash_flows.amount ELSE 0 END) - SUM(CASE WHEN cash_flows.type = 'credit' THEN cash_flows.amount ELSE 0 END) as balance")
            : DB::raw("SUM(CASE WHEN cash_flows.type = 'credit' THEN cash_flows.amount ELSE 0 END) - SUM(CASE WHEN cash_flows.type = 'debit' THEN cash_flows.amount ELSE 0 END) as balance");

        // Query to get the accounts with the specified type and their balances for the given date range and team
        $accounts = DB::table('accounts')
            ->leftJoin('cash_flows', function ($join) use ($year, $team_id) {
                $join->on('accounts.id', '=', 'cash_flows.account_id')
                    ->where('cash_flows.team_id', '=', $team_id)
                    ->whereYear('cash_flows.transaction_date', '<=', $year);
            })
            ->select(
                'accounts.code as account_code',
                'accounts.accountName as account_name',
                $balanceExpression
            )
            ->where('accounts.team_id', $team_id)
            ->where('accounts.accountType', $accountType) // Filter for accounts with type
            ->groupBy('accounts.id', 'accounts.code', 'accounts.accountName')
            ->when($specificAccountCode, function ($query) use ($specificAccountCode) {
                $query->where('accounts.code', $specificAccountCode);
            })
            ->get();

        // Apply the MoneyCast to balance for each account
        $accounts->transform(function ($account) {
            // Apply MoneyCast to the balance field
            $account->balance = (new MoneyCast())->get(null, 'balance', $account->balance, []);
            return $account;
        });

        return $accounts;
    }

    protected function getTotalBalanceBeforeYear($year, $accountType, $team_id)
    {
        $balanceExpression = $accountType === 'Revenue'
            ? DB::raw("SUM(CASE WHEN cash_flows.type = 'debit' THEN cash_flows.amount ELSE 0 END) - SUM(CASE WHEN cash_flows.type = 'credit' THEN cash_flows.amount ELSE 0 END) as balance")
            : DB::raw("SUM(CASE WHEN cash_flows.type = 'credit' THEN cash_flows.amount ELSE 0 END) - SUM(CASE WHEN cash_flows.type = 'debit' THEN cash_flows.amount ELSE 0 END) as balance");

        // Query to calculate balance before the specified year
        $totalBalance = DB::table('accounts')
            ->leftJoin('cash_flows', function ($join) use ($year, $team_id) {
                $join->on('accounts.id', '=', 'cash_flows.account_id')
                    ->where('cash_flows.team_id', '=', $team_id)
                    ->whereYear('cash_flows.transaction_date', '<', $year); // Before the specified year
            })
            ->select($balanceExpression)
            ->where('accounts.team_id', $team_id)
            ->where('accounts.accountType', $accountType) // Filter account type
            ->value('balance'); // Get the total balance value

        // If null, set to 0
        $totalBalance = $totalBalance ?? 0;

        // Apply MoneyCast to the balance
        $totalBalance = (new MoneyCast())->get(null, 'balance', $totalBalance, []);

        return $totalBalance;
    }


    protected static function calculateRevenue($year, $operator, $team_id): float
    {

        $totals = LabaRugi::query()
            ->where('team_id', $team_id)
            ->select('type')
            ->selectRaw('SUM(debit - credit) as total')
            ->whereYear('transaction_date', $operator, $year)
            ->groupBy('type')
            ->pluck('total', 'type')
            ->toArray();

        $profitAccount = CashFlow::query()
            ->join('accounts', 'cash_flows.account_id', '=', 'accounts.id')
            ->where('cash_flows.team_id', $team_id) // Specify the team_id from cash_flows table
            ->where('accounts.code', '3-310')
            ->whereYear('transaction_date', $operator, $year)
            ->sum('amount');

        $pendapatan = abs($totals['Revenue'] ?? 0) + $profitAccount;
        $pengeluaran = abs($totals['Expense'] ?? 0);
        $hpp = abs($totals['UPC'] ?? 0);

        return ($pendapatan - $pengeluaran - $hpp) / 100;
    }
}
