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
            CREATE OR REPLACE FUNCTION update_product_stock_transaction_status()
            RETURNS trigger AS $$
            BEGIN
                IF ((NEW.transaction_status_id = 7) AND (OLD.transaction_status_id != 7)) THEN
                    UPDATE products
                    SET stock_quantity = stock_quantity + amount_of_product
                    FROM detail_transactions
                    WHERE products.id = product_id
                    AND transaction_id = NEW.id;

                ELSIF ((OLD.transaction_status_id = 7) AND (NEW.transaction_status_id != 7)) THEN
                    UPDATE products
                    SET stock_quantity = stock_quantity - amount_of_product
                    FROM detail_transactions
                    WHERE products.id = product_id
                    AND transaction_id = NEW.id;
                END IF;
                RETURN NULL;
            END
            $$ LANGUAGE plpgsql;

            CREATE OR REPLACE TRIGGER trigger_product_and_transaction
            AFTER UPDATE ON transactions
            FOR EACH ROW
            EXECUTE PROCEDURE update_product_stock_transaction_status()
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('
        DROP TRIGGER \'trigger_product_and_transaction\'
        DROP FUNCTION update_product_stock_transaction_status
        ');
    }
};
