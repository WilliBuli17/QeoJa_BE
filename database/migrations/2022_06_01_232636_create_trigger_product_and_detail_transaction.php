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
            CREATE OR REPLACE FUNCTION update_product_stock_transaction()
            RETURNS trigger AS $$
            BEGIN
                IF TG_OP = \'INSERT\' THEN
                    UPDATE products
                    SET stock_quantity = stock_quantity - NEW.amount_of_product
                    WHERE products.id = NEW.product_id;

                ELSIF TG_OP = \'UPDATE\' THEN
                    UPDATE products
                    SET stock_quantity = stock_quantity + OLD.amount_of_product - NEW.amount_of_product
                    WHERE products.id = NEW.product_id;

                ELSIF TG_OP = \'DELETE\' THEN
                    UPDATE products
                    SET stock_quantity = stock_quantity + OLD.amount_of_product
                    WHERE products.id = OLD.product_id;
                END IF;
                RETURN NULL;
            END
            $$ LANGUAGE plpgsql;

            CREATE OR REPLACE TRIGGER trigger_product_and_detail_transaction
            AFTER INSERT OR UPDATE OR DELETE ON detail_transactions
            FOR EACH ROW
            EXECUTE PROCEDURE update_product_stock_transaction()
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
        DROP TRIGGER \'trigger_product_and_detail_transaction\'
        DROP FUNCTION update_product_stock_transaction
        ');
    }
};
