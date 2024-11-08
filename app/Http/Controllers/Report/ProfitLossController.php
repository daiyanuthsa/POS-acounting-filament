<?php

namespace App\Http\Controllers\Report;

use App\Casts\MoneyCast;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Illuminate\Http\Request;

class ProfitLossController extends Controller
{
    public function index()
    {
        $startDate = request('tableFilters.from_date.from_date')
            ?? date('Y-m-d', strtotime('-1 week'));

        $endDate = request('tableFilters.to_date.to_date') ?? date('Y-m-d');
        $merchant = Auth::user()->teams()->first();

        $revenue = $this->getAccountBalances($startDate, $endDate, 'Reveue',$merchant->id);
        $expense = $this->getAccountBalances($startDate, $endDate, 'Expense', $merchant->id);
        $costOfGoods = $this->getAccountBalances($startDate, $endDate, 'Reveue', $merchant->id);

        return $startDate . ' - ' . $endDate;
    }

    protected function getAccountBalances($startDate, $endDate, $accountType, $team_id)
    {
        // Query to get the accounts with the specified type and their balances for the given date range and team
        $accounts = DB::table('accounts')
            ->leftJoin('cash_flows', function ($join) use ($startDate, $endDate, $team_id) {
                $join->on('accounts.id', '=', 'cash_flows.account_id')
                    ->where('cash_flows.team_id', '=', $team_id)
                    ->whereBetween('cash_flows.transaction_date', [$startDate, $endDate]);
            })
            ->select(
                'accounts.code as account_code',
                'accounts.accountName as account_name',
                DB::raw("SUM(CASE WHEN cash_flows.type = 'debit' THEN cash_flows.amount ELSE 0 END) - SUM(CASE WHEN cash_flows.type = 'credit' THEN cash_flows.amount ELSE 0 END) as balance")
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

}

