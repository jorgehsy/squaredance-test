<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserProduct extends Pivot
{
    public static $APPROVED = 'approved';
    public static $PENDING = 'pending';
    public static $REJECTED = 'rejected';

    /**
    * Return the product owner
    */
    public function owner() {
        return $this->belongsTo(User::class);
    }

    /**
    * Return the product
    */
    public function product() {
        return $this->belongsTo(Product::class);
    }
}
