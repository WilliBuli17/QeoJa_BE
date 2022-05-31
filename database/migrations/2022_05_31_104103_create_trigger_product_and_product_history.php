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
            CREATE OR REPLACE FUNCTION update_product_stock()
            RETURNS trigger AS $$
            BEGIN
                IF TG_OP = \'INSERT\' THEN
                    IF NEW.history_category = \'in\' THEN
                        UPDATE products
                        SET stock_quantity = stock_quantity + NEW.amount_of_product
                        WHERE products.id = NEW.product_id;

                    ELSIF NEW.history_category = \'out\' THEN
                        UPDATE products
                        SET stock_quantity = stock_quantity - NEW.amount_of_product
                        WHERE products.id = NEW.product_id;
                    END IF;

                ELSIF TG_OP = \'UPDATE\' THEN
                    IF (OLD.product_id = NEW.product_id) AND (OLD.history_category = NEW.history_category) THEN
                        IF NEW.history_category = \'in\' THEN
                            IF (OLD.deleted_at IS NULL) AND (NEW.deleted_at IS NOT NULL) THEN
                                UPDATE products
                                SET stock_quantity = stock_quantity - NEW.amount_of_product
                                WHERE products.id = NEW.product_id;

                            ELSIF (OLD.deleted_at IS NOT NULL) AND (NEW.deleted_at IS NULL) THEN
                                UPDATE products
                                SET stock_quantity = stock_quantity + NEW.amount_of_product
                                WHERE products.id = NEW.product_id;

                            ELSE
                                UPDATE products
                                SET stock_quantity = stock_quantity - OLD.amount_of_product + NEW.amount_of_product
                                WHERE products.id = NEW.product_id;
                            END IF;

                        ELSIF NEW.history_category = \'out\' THEN
                            IF (OLD.deleted_at IS NULL) AND (NEW.deleted_at IS NOT NULL) THEN
                                UPDATE products
                                SET stock_quantity = stock_quantity + NEW.amount_of_product
                                WHERE products.id = NEW.product_id;

                            ELSIF (OLD.deleted_at IS NOT NULL) AND (NEW.deleted_at IS NULL) THEN
                                UPDATE products
                                SET stock_quantity = stock_quantity - NEW.amount_of_product
                                WHERE products.id = NEW.product_id;

                            ELSE
                                UPDATE products
                                SET stock_quantity = stock_quantity + OLD.amount_of_product - NEW.amount_of_product
                                WHERE products.id = NEW.product_id;
                            END IF;
                        END IF;

                    ELSIF (OLD.product_id != NEW.product_id) AND (OLD.history_category = NEW.history_category) THEN
                        IF NEW.history_category = \'in\' THEN
                            UPDATE products
                            SET stock_quantity = stock_quantity - OLD.amount_of_product
                            WHERE products.id = OLD.product_id;

                            UPDATE products
                            SET stock_quantity = stock_quantity + NEW.amount_of_product
                            WHERE products.id = NEW.product_id;

                        ELSIF NEW.history_category = \'out\' THEN
                            UPDATE products
                            SET stock_quantity = stock_quantity + OLD.amount_of_product
                            WHERE products.id = OLD.product_id;

                            UPDATE products
                            SET stock_quantity = stock_quantity - NEW.amount_of_product
                            WHERE products.id = NEW.product_id;
                        END IF;

                    ELSIF (OLD.product_id = NEW.product_id) AND (OLD.history_category != NEW.history_category) THEN
                        IF (OLD.history_category = \'in\') AND (NEW.history_category = \'out\') THEN
                            UPDATE products
                            SET stock_quantity = stock_quantity - OLD.amount_of_product - NEW.amount_of_product
                            WHERE products.id = NEW.product_id;

                        ELSIF (OLD.history_category = \'out\') AND (NEW.history_category = \'in\') THEN
                            UPDATE products
                            SET stock_quantity = stock_quantity + OLD.amount_of_product + NEW.amount_of_product
                            WHERE products.id = NEW.product_id;
                        END IF;

                    ELSIF (OLD.product_id != NEW.product_id) AND (OLD.history_category != NEW.history_category) THEN
                        IF (OLD.history_category = \'in\') AND (NEW.history_category = \'out\') THEN
                            UPDATE products
                            SET stock_quantity = stock_quantity - OLD.amount_of_product
                            WHERE products.id = OLD.product_id;

                            UPDATE products
                            SET stock_quantity = stock_quantity - NEW.amount_of_product
                            WHERE products.id = NEW.product_id;

                        ELSIF (OLD.history_category = \'out\') AND (NEW.history_category = \'in\') THEN
                            UPDATE products
                            SET stock_quantity = stock_quantity + OLD.amount_of_product
                            WHERE products.id = OLD.product_id;

                            UPDATE products
                            SET stock_quantity = stock_quantity + NEW.amount_of_product
                            WHERE products.id = NEW.product_id;
                        END IF;
                    END IF;
                END IF;
                RETURN NULL;
            END
            $$ LANGUAGE plpgsql;

            CREATE OR REPLACE TRIGGER trigger_product_and_product_history
            AFTER INSERT OR UPDATE ON product_histories
            FOR EACH ROW
            EXECUTE PROCEDURE update_product_stock()
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
        DROP TRIGGER \'trigger_product_and_product_history\'
        DROP FUNCTION update_product_stock
        ');
    }
};
