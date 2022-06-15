<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            CREATE OR REPLACE TRIGGER trigger_product_and_transaction
                AFTER UPDATE ON transactions
                FOR EACH ROW
            BEGIN
                IF ((NEW.transaction_status_id = 7) AND (OLD.transaction_status_id != 7)) THEN
                    UPDATE products
                    INNER JOIN detail_transactions ON (products.id = product_id)
                    SET stock_quantity = stock_quantity + amount_of_product
                    WHERE transaction_id = NEW.id;

                ELSEIF ((OLD.transaction_status_id = 7) AND (NEW.transaction_status_id != 7)) THEN
                    UPDATE products
                    INNER JOIN detail_transactions ON (products.id = product_id)
                    SET stock_quantity = stock_quantity - amount_of_product
                    WHERE transaction_id = NEW.id;
                END IF;
            END
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER \'trigger_product_and_transaction\'');
    }
};
