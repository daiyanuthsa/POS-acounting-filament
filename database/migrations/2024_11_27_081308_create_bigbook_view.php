<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE VIEW bigbook_view AS
            SELECT 
                cf.id AS bigbook_id,
                cf.transaction_date,
                cf.account_id,
                a.accountName AS Account,
                a.accountType AS account_type,
                a.team_id,
                a.user_id,
                CASE 
                    WHEN cf.type = 'debit' THEN cf.amount 
                    ELSE 0 
                END AS debit,
                CASE 
                    WHEN cf.type = 'credit' THEN cf.amount 
                    ELSE 0 
                END AS credit,
                cf.description,
                SUM(
                    CASE 
                        WHEN a.accountType IN ('Asset', 'Expense', 'UPC') THEN
                            CASE WHEN cf.type = 'debit' THEN cf.amount ELSE -cf.amount END
                        WHEN a.accountType IN ('Liability', 'Equity', 'Revenue') THEN
                            CASE WHEN cf.type = 'credit' THEN cf.amount ELSE -cf.amount END
                        ELSE 0
                    END
                ) OVER (
                    PARTITION BY cf.account_id, a.team_id
                    ORDER BY cf.transaction_date, cf.id
                ) AS running_balance
            FROM 
                cash_flows cf
            LEFT JOIN 
                accounts a ON cf.account_id = a.id
            ORDER BY 
                a.team_id, 
                cf.account_id, 
                cf.transaction_date, 
                cf.id;
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS bigbook_view;");
    }
};
