<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        return [
            'name' => fake()->word(),
            'monthly_inventory' => fake()->randomDigitNotNull()
        ];
    }

    /**
    * Indicate that the product is active.
    *
    * @return \Illuminate\Database\Eloquent\Factories\Factory
    */
    public function active(){
        return $this->state(function (array $attributes) {
            return [
                'status' => Product::$ACTIVE,
            ];
        });
    }

    /**
    * Indicate that the product is active.
    *
    * @return \Illuminate\Database\Eloquent\Factories\Factory
    */
    public function onHold(){
        return $this->state(function (array $attributes) {
            return [
                'status' => Product::$ONHOLD,
            ];
        });
    }

    /**
    * Indicate that the product is active.
    *
    * @return \Illuminate\Database\Eloquent\Factories\Factory
    */
    public function expired(){
        return $this->state(function (array $attributes) {
            return [
                'status' => Product::$EXPIRED,
            ];
        });
    }

    public function configure()
    {
        return $this->afterMaking(function (Product $product) {
            //
        })->afterCreating(function (Product $product) {
            if($product->status === Product::$ACTIVE){
                Transaction::factory()
                    ->count(3)
                    ->for($product)
                    ->for(User::factory()->create())
                    ->create();
            }
        });
    }
}
