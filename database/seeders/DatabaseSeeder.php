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
            'name' => 'Owner',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ])->getAttributes();

        $user = User::create([
            'reference' => 'employee',
            'email' => 'bw@mail.com',
            'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'password' => bcrypt('12345678'),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'deleted_at' => null,
        ])->getAttributes();

        $user2 = User::create([
            'reference' => 'customer',
            'email' => 'bw2@mail.com',
            'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'password' => bcrypt('12345678'),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'deleted_at' => null,
        ])->getAttributes();

        Employee::create([
            'name' => 'Willi Buli Employee',
            'gander' => 'man',
            'phone' => '021111111111',
            'address' => 'qwertyuiop',
            'date_join' => Carbon::now()->format('Y-m-d'),
            'picture' => 'no-image.jpg',
            'role_id' => $role['id'],
            'user_id' => $user['id'],
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'deleted_at' => null,
        ]);

        Customer::create([
            'name' => 'Willi Buli Customer',
            'phone' => '021111111111',
            'picture' => 'no-image.jpg',
            'user_id' => $user2['id'],
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'deleted_at' => null,
        ]);

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
                'volume' => 10 * $i,
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
    }
}
