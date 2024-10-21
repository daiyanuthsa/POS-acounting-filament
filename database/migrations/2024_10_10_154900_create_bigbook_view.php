<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateBigbookView extends Migration
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
                `cf`.`id` AS `bigbook_id`,
                `cf`.`transaction_date` AS `transaction_date`,
                `cf`.`account_id` AS `account_id`,
                `a`.`accountname` AS `Account`,
                `a`.`team_id` AS `team_id`,
                `a`.`user_id` AS `user_id`,
                (CASE 
                    WHEN (`cf`.`type` = 'debit') THEN `cf`.`amount`
                    ELSE 0
                END) AS `debit`,
                (CASE 
                    WHEN (`cf`.`type` = 'credit') THEN `cf`.`amount`
                    ELSE 0
                END) AS `credit`,
                `cf`.`description` AS `description`
            FROM 
                `cash_flows` `cf` 
            LEFT JOIN 
                `accounts` `a` 
            ON 
                (`cf`.`account_id` = `a`.`id`)
            ORDER BY 
                `cf`.`transaction_date`, `a`.`accountname`
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS bigbook_view');
    }
}
