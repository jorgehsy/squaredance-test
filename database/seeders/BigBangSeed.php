<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use App\Models\UserProduct;
use Illuminate\Database\Seeder;

class BigBangSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()
            ->count(2)
            ->hasAttached(
                Product::factory()->count(2)->active(),
                ['status' => UserProduct::$APPROVED]
            )
            ->hasAttached(
                Product::factory()->count(1)->onHold(),
                ['status' => UserProduct::$PENDING]
            )
            ->hasAttached(
                Product::factory()->count(1)->onHold(),
                ['status' => UserProduct::$REJECTED]
            )
            ->hasAttached(
                Product::factory()->count(1)->expired(),
                ['status' => UserProduct::$APPROVED]
            )
            ->create();
    }
}
