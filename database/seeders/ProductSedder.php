<?php

namespace Database\Seeders;

use App\Models\Product;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ProductSedder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for ($i=1; $i < 20; $i++) {       
            $data = new Product();
            $data->title = $faker->sentence;
            $data->price = rand(10.00,15.90);
            $data->image = 'public/uploads/product/1.png';
            $data->description = $faker->text;
            $data->user_id = 1;
            $data->save();
        }
    }
}
