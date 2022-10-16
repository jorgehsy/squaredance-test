<?php

namespace App\Observers;

use App\Models\Product;
use App\Notifications\ProductInventoryDepleted;
use App\Notifications\ProductStatusChanged;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function created(Product $product)
    {
        //
    }

    /**
     * Handle the Product "updated" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function updated(Product $product)
    {
        $user = $product->owner;

        if ( !$product->hasAvailableInventory() ){
            $user->notify(new ProductInventoryDepleted($product));
        }
    }

    /**
    * Handle the Product "updating" event.
    *
    * @param  \App\Models\Product  $product
    * @return void
    */
    public function updating(Product $product)
    {
        $user = $product->owner;

        if ( $product->isDirty('status') ){
            $user->notify(new ProductStatusChanged($product));
        }

    }

    /**
     * Handle the Product "deleted" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function deleted(Product $product)
    {
        //
    }

    /**
     * Handle the Product "restored" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function restored(Product $product)
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function forceDeleted(Product $product)
    {
        //
    }
}
