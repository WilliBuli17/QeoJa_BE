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
        // DB::unprepared('
        //     CREATE OR REPLACE TRIGGER trigger_product_and_detail_transaction_insert
        //         AFTER INSERT ON detail_transactions
        //         FOR EACH ROW
        //     BEGIN
        //         UPDATE products
        //         SET stock_quantity = stock_quantity - NEW.amount_of_product
        //         WHERE products.id = NEW.product_id;
        //     END
        // ');

        // DB::unprepared('
        //     CREATE OR REPLACE TRIGGER trigger_product_and_detail_transaction_update
        //         AFTER UPDATE ON detail_transactions
        //         FOR EACH ROW
        //     BEGIN
        //         UPDATE products
        //         SET stock_quantity = stock_quantity + OLD.amount_of_product - NEW.amount_of_product
        //         WHERE products.id = NEW.product_id;
        //     END
        // ');

        // DB::unprepared('
        //     CREATE OR REPLACE TRIGGER trigger_product_and_detail_transaction_delete
        //         AFTER DELETE ON detail_transactions
        //         FOR EACH ROW
        //     BEGIN
        //         UPDATE products
        //         SET stock_quantity = stock_quantity + OLD.amount_of_product
        //         WHERE products.id = OLD.product_id;
        //     END
        // ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // DB::unprepared('DROP TRIGGER \'trigger_product_and_detail_transaction_insert\'');
        // DB::unprepared('DROP TRIGGER \'trigger_product_and_detail_transaction_update\'');
        // DB::unprepared('DROP TRIGGER \'trigger_product_and_detail_transaction_delete\'');
    }
};
