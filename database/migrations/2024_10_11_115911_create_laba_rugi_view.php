<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::statement("
            CREATE VIEW laba_rugi AS
            SELECT 
                cf.id AS id,
                cf.transaction_date AS transaction_date,
                cf.account_id AS account_id,
                a.accountName AS account,
                a.accountType AS type,
                a.team_id AS team_id,
                a.user_id AS user_id,
                (CASE WHEN cf.type = 'debit' THEN cf.amount ELSE 0 END) AS debit,
                (CASE WHEN cf.type = 'credit' THEN cf.amount ELSE 0 END) AS credit,
                cf.description AS description
            FROM 
                cash_flows cf
            LEFT JOIN 
                accounts a ON cf.account_id = a.id
            WHERE 
                a.accountType LIKE 'Expense' OR a.accountType LIKE 'Revenue' OR a.accountType LIKE 'UPC'
            ORDER BY 
                cf.transaction_date, a.accountName
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laba_rugi');
    }
};
