<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Employee;
use App\Models\Customer;
use App\Models\Category;
use App\Models\Suplier;
use App\Models\Product;
use App\Models\City;
use App\Models\Address;
use App\Models\TransactionStatus;
use App\Models\ExpeditionTruck;
use App\Models\BankPayment;
use App\Models\ProductHistory;
use App\Models\Transaction;
use App\Models\DetailTransaction;

use DateTime;
use DateInterval;
use DatePeriod;

use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $role = Role::create([
            'name' => 'Super Admin',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ])->getAttributes();

        $user = User::create([
            'reference' => 'employee',
            'email' => 'superAdmin@mail.com',
            'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'password' => bcrypt('12345678'),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'deleted_at' => null,
        ])->getAttributes();

        $employee = Employee::create([
            'name' => 'Super Admin',
            'gander' => 'man',
            'phone' => '021111111111',
            'address' => 'Super Admin',
            'date_join' => Carbon::now()->format('Y-m-d'),
            'picture' => 'no-image.jpg',
            'role_id' => $role['id'],
            'user_id' => $user['id'],
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'deleted_at' => null,
        ])->getAttributes();

        $user2 = User::create([
            'reference' => 'customer',
            'email' => 'superCustomer@mail.com',
            'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'password' => bcrypt('12345678'),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'deleted_at' => null,
        ])->getAttributes();

        $customer = Customer::create([
            'name' => 'Super Customer',
            'phone' => '021111111111',
            'picture' => 'no-image.jpg',
            'user_id' => $user2['id'],
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'deleted_at' => null,
        ])->getAttributes();

        Role::create([
            'name' => 'Owner',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ])->getAttributes();

        Role::create([
            'name' => 'Manager',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ])->getAttributes();

        Role::create([
            'name' => 'Admin Penjualan',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ])->getAttributes();

        Role::create([
            'name' => 'Admin Gudang',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ])->getAttributes();

        Role::create([
            'name' => 'Admin Ekspedisi',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ])->getAttributes();

        TransactionStatus::create([
            'name' => 'Menunggu Konfirmasi',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ])->getAttributes();

        TransactionStatus::create([
            'name' => 'Sedang Dikemas',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ])->getAttributes();

        TransactionStatus::create([
            'name' => 'Dalam Antrian Pengiriman',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ])->getAttributes();

        TransactionStatus::create([
            'name' => 'Dalam Pengiriman',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ])->getAttributes();

        TransactionStatus::create([
            'name' => 'Pengiriman Selesai',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ])->getAttributes();

        TransactionStatus::create([
            'name' => 'Pengiriman Selesai - Konfirmasi Pengguna',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ])->getAttributes();

        TransactionStatus::create([
            'name' => 'Transaksi Dibatalkan',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ])->getAttributes();






        for ($i = 1; $i <= 10; $i++) {
            Suplier::create([
                'name' => 'Suplier ' . $i,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ])->getAttributes();
        }

        for ($i = 1; $i <= 10; $i++) {
            Category::create([
                'name' => 'Category ' . $i,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ])->getAttributes();
        }

        for ($i = 1; $i <= 100; $i++) {
            Product::create([
                'name' => 'Product ' . $i,
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam tortor diam, ornare a diam eget, bibendum facilisis libero. Nunc porta at nisi sed interdum. Aenean semper, ipsum sed placerat dapibus, dolor enim ultrices velit, non placerat turpis sem eget velit. Suspendisse eget congue quam, nec ornare metus. Cras blandit, orci vel cursus iaculis, metus augue luctus ante, eu laoreet magna dolor in magna. Suspendisse quis ligula est. Cras malesuada leo eu hendrerit congue. Mauris non lacus eu est luctus aliquam ac sit amet nibh. Sed non ipsum tellus. Fusce pretium consequat massa id pretium. Quisque consectetur massa sed elit elementum, non cursus nisl ullamcorper. Donec finibus arcu nec quam maximus tincidunt.',
                'unit' => 'Dus',
                'volume' => rand(5, 25),
                'price' => 10000 * $i,
                'picture' => 'no-image.jpg',
                'stock_quantity' => 0,
                'category_id' => rand(1, 10),
                'suplier_id' => rand(1, 10),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => null,
            ]);
        }











        for ($i = 1; $i <= 10; $i++) {
            City::create([
                'name' => 'City ' . $i,
                'expedition_cost' => rand(1, 8) / 10,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ])->getAttributes();
        }

        for ($i = 1; $i <= 10; $i++) {
            Address::create([
                'address' => 'Address ' . $i,
                'customer_id' => $customer['id'],
                'city_id' => $i,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ])->getAttributes();
        }

        for ($i = 1; $i <= 10; $i++) {
            ExpeditionTruck::create([
                'license_id' => 'Expedition Truck ' . $i,
                'min_volume' => rand(200, 400),
                'max_volume' => rand(500, 1000),
                'picture' => 'no-image.jpg',
                'status' => 'available',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ])->getAttributes();
        }

        for ($i = 1; $i <= 10; $i++) {
            BankPayment::create([
                'bank_name' => 'Bank Payment ' . $i,
                'account_name' => 'Account Name BankPayment ' . $i,
                'account_number' => rand(10000000, 999999999999),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ])->getAttributes();
        }











        //product history
        $begin = new DateTime('2017-01-01');
        $end = new DateTime('2023-01-01');

        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);

        foreach ($period as $dt) {
            $product_id = rand(1, 100);
            $amount_of_product = rand(50, 100);
            $product_price = (10000 * $product_id) / 2;
            $total_price = $amount_of_product * $product_price;

            ProductHistory::create([
                'history_category' => 'in',
                'history_date' => $dt,
                'amount_of_product' => $amount_of_product,
                'product_price' => $product_price,
                'total_price' => $total_price,
                'product_expired_date' => Carbon::now()->addYears(12)->format('Y-m-d'),
                'product_id' => $product_id,
                'created_by' => $employee['id'],
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => null,
            ]);
        }

        foreach ($period as $dt) {
            $product_id = rand(1, 100);
            $amount_of_product = rand(10, 20);
            $product_price = (10000 * $product_id);
            $total_price = $amount_of_product * $product_price;

            ProductHistory::create([
                'history_category' => 'out',
                'history_date' => $dt,
                'amount_of_product' => $amount_of_product,
                'product_price' => $product_price,
                'total_price' => $total_price,
                'product_expired_date' => Carbon::now()->addYears(2)->format('Y-m-d'),
                'product_id' => $product_id,
                'created_by' => $employee['id'],
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => null,
            ]);
        }

        // transaction
        foreach ($period as $dt) {
            // $product_id = rand(1, 100);
            $lopfu = rand(10, 20);
            $transaction = Transaction::create([
                'subtotal_price' => 1,
                'tax' => 2,
                'shipping_cost' => 3,
                'grand_total_price' => 6,
                'message' => '10',
                'total_volume_product' => 10,
                'receipt_of_payment' => 'no-image.jpg',
                'customer_id' => $customer['id'],
                'address_id' => rand(1, 10),
                'bank_payment_id' => rand(1, 10),
                'transaction_status_id' => rand(5,6),
                'created_at' => Carbon::parse($dt)->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::parse($dt)->format('Y-m-d H:i:s'),
            ])->getAttributes();

            //detail transaction
            for ($i = 1; $i <= $lopfu; $i++) {
                $status = ['success', 'fail'];
                DetailTransaction::create([
                    // 'amount_of_product' => $lopfu,
                    // 'product_price' => $lopfu,
                    // 'total_price' => $lopfu*$lopfu,

                    'amount_of_product' => 1,
                    'product_price' => 1,
                    'total_price' => 2,
                    'status' => $status[rand(0,1)],
                    'transaction_id' => $transaction['id'],
                    'product_id' => 1,
                    'created_at' => Carbon::parse($dt)->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::parse($dt)->format('Y-m-d H:i:s'),
                ]);
            }
        }
    }
}
