<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Customer;
use Illuminate\Database\Seeder;

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

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // User::create([
        //     'name'      =>  'Admin',
        //     'email'     =>  'admin@gmail.com',
        //     'password'  =>  bcrypt('password'),
        //     'role'      =>  'admin'
        // ]);

        // User::create([
        //     'name'      =>  'User',
        //     'email'     =>  'user@gmail.com',
        //     'password'  =>  bcrypt('password'),
        //     'role'      =>  'user'
        // ]);




        
        Setting::create(
            [
            'name'      =>  'customer_code_prefix',
            'value'     =>  'CU-',
        ]);

        Setting::create([
            'name'      =>  'invoice_code_prefix',
            'value'     =>  'IN-',
        ]);

        Setting::create([
            'name'      =>  'minimum_itemtotal',
            'value'     =>  500.00,
        ]);

        

        Product::create([
            'product_name'      =>  'Flex',
            'product_price'     =>  150.00,
            'status'            =>  1
        ]);

        Product::create([
            'product_name'      =>  'SAV',
            'product_price'     =>  170.00,
            'status'            =>  1
        ]);

        // Customer::create([
            
        //     'count_id'     =>  1,
        //     'customer_code'      =>  'CU-0001',
        //     'customer_name'     =>  'Walk-In Customer',
        //     'customer_phone'    =>  NULL,
        //     'customer_email'    =>  NULL,
        //     'customer_amount_due'    => 0.00,
        //     'created_by'    =>  'Admin'

        // ]);

       


    }
}
