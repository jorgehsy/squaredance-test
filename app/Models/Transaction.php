<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    /**
    * The table associated with the model.
    *
    * @var string
    */
    protected $table = 'transaction';

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