<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Employee;
use App\Models\Customer;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\City;
use App\Models\Address;
use App\Models\TransactionStatus;
use App\Models\ExpeditionTruck;
use App\Models\BankPayment;
use App\Models\ProductHistory;
use App\Models\Transaction;
use App\Models\DetailTransaction;
use App\Models\TransactionShipping;

use DateTime;
use DateInterval;
use DatePeriod;

use Illuminate\Support\Collection;

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

        $imageProfil =  [
            '1.jpg',
            '2.jpg',
            '3.jpg',
            '1.jpg',
            '5.jpg',
        ];

        for ($i = 1; $i <= 5; $i++) {
            $user = User::create([
                'reference' => 'employee',
                'email' => 'employee' . $i . '@mail.com',
                'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'password' => bcrypt('12345678'),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => null,
            ])->getAttributes();

            Employee::create([
                'name' => 'Employee ' . $i,
                'gander' => 'man',
                'phone' => '021111111111',
                'address' => 'Employee ' . $i . ' Address',
                'date_join' => Carbon::now()->format('Y-m-d'),
                'picture' => 'storage/employee/' . $imageProfil[$i - 1],
                'role_id' => $i + 1,
                'user_id' => $user['id'],
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => null,
            ])->getAttributes();
        }

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
            Supplier::create([
                'name' => 'Supplier ' . $i,
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

        $volume =  [
            5100,
            20100,
            10100,
            50100,
            64000,
            14720,
            16800,
            96000,
            4500,
            6000,
        ];

        $unit =  [
            'Karung1',
            'Karung2',
            'Karung3',
            'Karung4',
            'Dus1',
            'Dus2',
            'Dus3',
            'Dus4',
            'Dus5',
            'Dus6',
        ];

        $imageProduct =  [
            '1.jpg',
            '2.jpg',
            '3.jpg',
            '1.jpg',
            '5.jpg',
            '6.jpg',
            '7.jpg',
            '8.jpg',
            '9.jpg',
            '10.png',
            '11.png',
            '12.jpg',
            '13.jpg',
            '14.jpg',
            '15.jpg',
            '16.jpg',
            '17.jpg',
            '18.jpg',
            '19.jpg',
            '20.jpg',
        ];

        for ($i = 1; $i <= 50; $i++) {
            $randomProduct = rand(0, 9);

            Product::create([
                'name' => 'Product ' . $i,
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam tortor diam, ornare a diam eget, bibendum facilisis libero. Nunc porta at nisi sed interdum. Aenean semper, ipsum sed placerat dapibus, dolor enim ultrices velit, non placerat turpis sem eget velit. Suspendisse eget congue quam, nec ornare metus. Cras blandit, orci vel cursus iaculis, metus augue luctus ante, eu laoreet magna dolor in magna. Suspendisse quis ligula est. Cras malesuada leo eu hendrerit congue. Mauris non lacus eu est luctus aliquam ac sit amet nibh. Sed non ipsum tellus. Fusce pretium consequat massa id pretium. Quisque consectetur massa sed elit elementum, non cursus nisl ullamcorper. Donec finibus arcu nec quam maximus tincidunt.',
                'unit' => $unit[$randomProduct],
                'volume' => $volume[$randomProduct],
                'price' => 10000 * $i,
                'picture' => 'storage/product/' . $imageProduct[rand(0, 19)],
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
                'min_volume' => 18000000,
                'max_volume' => 24000000,
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
            $product_id = rand(1, 50);
            $amount_of_product = rand(500, 1000);
            $product_price = (10000 * $product_id) / 2;
            $total_price = $amount_of_product * $product_price;

            ProductHistory::create([
                'history_category' => 'in',
                'history_date' => $dt,
                'amount_of_product' => $amount_of_product,
                'product_price' => $product_price,
                'total_price' => $total_price,
                'product_expired_date' => Carbon::parse($dt)->addYears(12)->format('Y-m-d'),
                'product_id' => $product_id,
                'created_by' => $employee['id'],
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => Carbon::parse($dt)->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::parse($dt)->format('Y-m-d H:i:s'),
                'deleted_at' => null,
            ]);

            $amount_of_product = rand(10, 20);
            $product_price = (10000 * $product_id);
            $total_price = $amount_of_product * $product_price;

            ProductHistory::create([
                'history_category' => 'out',
                'history_date' => $dt,
                'amount_of_product' => $amount_of_product,
                'product_price' => $product_price,
                'total_price' => $total_price,
                'product_expired_date' => Carbon::parse($dt)->format('Y-m-d'),
                'product_id' => $product_id,
                'created_by' => $employee['id'],
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => Carbon::parse($dt)->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::parse($dt)->format('Y-m-d H:i:s'),
                'deleted_at' => null,
            ]);
        }

        // transaction
        foreach ($period as $dt) {
            $loop_collection = rand(10, 20);
            $collection = new Collection;
            $subtotal = 0;
            $total_volume = 0;

            for ($i = 1; $i <= $loop_collection; $i++) {
                $amount_of_product = rand(10, 20);
                $product_id = rand(1, 50);
                $product = Product::find($product_id);
                $subtotal = $subtotal + ($product->price * $amount_of_product);
                $total_volume = $total_volume + ($product->volume * $amount_of_product);

                $collection->push((object)[
                    'amount_of_product' => $amount_of_product,
                    'product_price' => $product->price,
                    'total_price' => $product->price * $amount_of_product,
                    'status' => 'success',
                    'product_id' => $product_id,
                ]);
            }

            $address = Address::leftJoin('cities', 'addresses.city_id', '=', 'cities.id')
                ->where('addresses.customer_id', '=', $customer['id'])
                ->get();
            $address_id = rand(0, 9);
            $transaction_status_id = rand(1, 6);

            $transaction = Transaction::create([
                'subtotal_price' => $subtotal,
                'tax' => $subtotal * 0.11,
                'shipping_cost' => $address[$address_id]->expedition_cost * $total_volume,
                'grand_total_price' => $subtotal + ($subtotal * 0.11) + ($address[$address_id]->expedition_cost * $total_volume),
                'message' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam tortor diam, ornare a diam eget, bibendum facilisis libero. Nunc porta at nisi sed interdum. Aenean semper, ipsum sed placerat dapibus, dolor enim ultrices velit, non placerat turpis sem eget velit. Suspendisse eget congue quam, nec ornare metus. Cras blandit, orci vel cursus iaculis, metus augue luctus ante, eu laoreet magna dolor in magna. Suspendisse quis ligula est. Cras malesuada leo eu hendrerit congue. Mauris non lacus eu est luctus aliquam ac sit amet nibh. Sed non ipsum tellus. Fusce pretium consequat massa id pretium. Quisque consectetur massa sed elit elementum, non cursus nisl ullamcorper. Donec finibus arcu nec quam maximus tincidunt.',
                'total_volume_product' => $total_volume,
                'receipt_of_payment' => 'no-image.jpg',
                'customer_id' => $customer['id'],
                'address_id' => $address[$address_id]->id,
                'bank_payment_id' => rand(1, 10),
                'transaction_status_id' => $transaction_status_id,
                'created_at' => Carbon::parse($dt)->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::parse($dt)->format('Y-m-d H:i:s'),
            ])->getAttributes();

            //detail transaction
            foreach ($collection as $c) {
                DetailTransaction::create([
                    'amount_of_product' => $c->amount_of_product,
                    'product_price' => $c->product_price,
                    'total_price' => $c->total_price,
                    'status' => $c->status,
                    'transaction_id' => $transaction['id'],
                    'product_id' => $c->product_id,
                    'created_at' => Carbon::parse($dt)->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::parse($dt)->format('Y-m-d H:i:s'),
                ]);
            }

            if ($transaction_status_id === 4) {
                TransactionShipping::create([
                    'transaction_id' => $transaction['id'],
                    'employee_id' => 6,
                    'expedition_truck_id' => rand(1, 10),
                    'delivery_date' => Carbon::parse($dt)->format('Y-m-d H:i:s'),
                    'created_at' => Carbon::parse($dt)->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::parse($dt)->format('Y-m-d H:i:s'),
                ]);
            }

            if ($transaction_status_id === 5 || $transaction_status_id === 6) {
                TransactionShipping::create([
                    'transaction_id' => $transaction['id'],
                    'employee_id' => 6,
                    'expedition_truck_id' => rand(1, 10),
                    'delivery_date' => Carbon::parse($dt)->format('Y-m-d H:i:s'),
                    'arrived_date' => Carbon::parse($dt)->addDays(2)->format('Y-m-d H:i:s'),
                    'created_at' => Carbon::parse($dt)->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::parse($dt)->format('Y-m-d H:i:s'),
                ]);
            }
        }
    }
}
