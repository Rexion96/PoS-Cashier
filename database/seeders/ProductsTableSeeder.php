<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($x = 1; $x <= 6; $x++){
            $product = Product::create([
                'name' => 'P'.$x,
                'price'=> $x,
            ]);
        }
    }
}
