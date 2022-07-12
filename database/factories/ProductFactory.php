<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $name = $this->faker->text(25);
        $price =  $this->faker->numberBetween($min=100 , $max=900);
        return [
            //
            'name' => $name,
            "slug" => Str::slug($name),
            'description' => $this->faker->text(500),
            'image' => 'default.jpg',

            'purchase_price' => $price,
            'sale_price' => $price + 50,
            'stock' => 100,
            'images' => json_encode(['default1.jpg','default2.jpg']),

        ];
    }
}
