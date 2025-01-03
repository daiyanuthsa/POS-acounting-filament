<?php

namespace App\Http\Controllers\Report;

use App\Casts\MoneyCast;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\LabaRugi;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use DB;
use Illuminate\Http\Request;

class BalanceSheet extends Controller
{
    //
    public function index()
    {
        if (!Auth::check()) {
            // Redirect to login if the user is not authenticated
            return redirect('/merchant')->with('error', 'Please login first.');
        }

        // Get the year from the request or default to current year
        $year = request('tableFilters.year.value') ?? date('Y');

        // Fetch the authenticated user's team
        $merchant = Auth::user()->teams()->first();
        if (!$merchant) {
            // Redirect to a specific path if no team is associated
            return redirect('/merchant');
        }

        // Get the balance sheet data for the given year and merchant's team
        $balanceSheetData = $this->getAccountBalances($year, $merchant->id);
        $additionalBalance= $this->calculateRevenue($year, $merchant->id);
        $pasiva = $this->getLiabilityAndEquityBalances($year, $merchant->id);

        foreach ($pasiva as $item) {
            if ($item->account_name === "Laba berjalan") {
                $item->balance += $additionalBalance;
            }
        }

        // Generate the PDF with the balance sheet data
        $pdf = PDF::loadView('report.balance-sheet', [
            'year' => $year,
            'merchant' => $merchant,
            'balanceSheetData' => $balanceSheetData,
            'pasiva' => $pasiva
        ])
            ->setPaper('a4', 'potrait');

        // Return the PDF as a stream (download in the browser)
        return $pdf->stream('Laporan Posisi Keuangan ' . $merchant->name . '_' . $year . '.pdf');
    }

    protected function getAccountBalances($year, $team_id)
    {
        // Query to get the accounts with type 'Asset' and their balances for the given year and team
        $accounts = DB::table('accounts')
            ->leftJoin('cash_flows', function ($join) use ($year, $team_id) {
                $join->on('accounts.id', '=', 'cash_flows.account_id')
                    ->where('cash_flows.team_id', '=', $team_id)
                    ->whereYear('cash_flows.transaction_date', '<=', $year);
            })
            ->select(
                'accounts.code as account_code',
                'accounts.accountName as account_name',
                'accounts.asset_type', // Include the asset_type
                DB::raw("SUM(CASE WHEN cash_flows.type = 'debit' THEN cash_flows.amount ELSE 0 END) - SUM(CASE WHEN cash_flows.type = 'credit' THEN cash_flows.amount ELSE 0 END) as balance")
            )
            ->where('accounts.team_id', $team_id)
            ->where('accounts.accountType', 'Asset') // Filter for accounts with type 'Asset'
            ->groupBy('accounts.id', 'accounts.code', 'accounts.accountName', 'accounts.asset_type') // Group by asset_type
            ->get();

        // Apply the MoneyCast to balance for each account
        $accounts->transform(function ($account) {
            // Apply MoneyCast to the balance field
            $account->balance = (new MoneyCast())->get(null, 'balance', $account->balance, []);
            return $account;
        });

        return $accounts;
    }
    protected function getLiabilityAndEquityBalances($year, $team_id)
    {
        // Query to get the accounts with type 'Liability' and 'Equity', and their balances for the given year and team
        $accounts = DB::table('accounts')
            ->leftJoin('cash_flows', function ($join) use ($year, $team_id) {
                $join->on('accounts.id', '=', 'cash_flows.account_id')
                    ->where('cash_flows.team_id', '=', $team_id)
                    ->whereYear('cash_flows.transaction_date', '<=', $year);
            })
            ->select(
                'accounts.code as account_code',
                'accounts.accountName as account_name',
                'accounts.accountType', // Include the accountType (Liability or Equity)
                DB::raw("SUM(CASE WHEN cash_flows.type = 'credit' THEN cash_flows.amount ELSE 0 END) - SUM(CASE WHEN cash_flows.type = 'debit' THEN cash_flows.amount ELSE 0 END) as balance")
            )
            ->where('accounts.team_id', $team_id)
            ->whereIn('accounts.accountType', ['Liability', 'Equity']) // Filter for accounts with type 'Liability' or 'Equity'
            ->groupBy('accounts.id', 'accounts.code', 'accounts.accountName', 'accounts.accountType') // Group by accountType
            ->get();

        // Apply the MoneyCast to balance for each account
        $accounts->transform(function ($account) {
            // Apply MoneyCast to the balance field
            $account->balance = (new MoneyCast())->get(null, 'balance', $account->balance, []);
            return $account;
        });

        return $accounts;
    }

    protected static function calculateRevenue($year, $teamId): float
    {
        $totals = LabaRugi::query()
            ->where('team_id', $teamId)
            ->select('type')
            ->selectRaw('SUM(debit - credit) as total')
            ->whereYear('transaction_date', '<=', $year)
            ->groupBy('type')
            ->pluck('total', 'type')
            ->toArray();

        $pendapatan = abs($totals['Revenue'] ?? 0);
        $pengeluaran = abs($totals['Expense'] ?? 0);
        $hpp = abs($totals['UPC'] ?? 0);

        return ($pendapatan - $pengeluaran - $hpp)/100;
    }




}
