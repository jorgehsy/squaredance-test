<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Pivot
{
    use HasFactory;

    /**
    * The table associated with the model.
    *
    * @var string
    */
    protected $table = 'transaction';

    public static function createSale(User $user, Product $product)
    {
        $product->sell();

        return $user->transactions()->create([
            "product_id" => $product->id
        ]);
    }

    /**
     * Return the product sold in the transaction
     */
    public function product(){
        return $this->belongsTo(Product::class);
    }

    /**
    * Return the user responsible for the transaction
    */
    public function user(){
        return $this->belongsTo(User::class);
    }
}
