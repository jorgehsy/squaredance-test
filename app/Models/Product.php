<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
    * The attributes that are mass assignable.
    *
    * @var array<int, string>
    */
    protected $fillable = [
        'name',
        'status',
        'monthly_inventory',
    ];

    public static $ACTIVE = 'active';
    public static $ONHOLD = 'on-hold';
    public static $EXPIRED = 'expired';

    /**
     * The user owner of the product which will get the paying amount when it gets sold
     */
    public function owners(){
        return $this->hasManyThrough(User::class, UserProduct::class, 'product_id', 'id', 'id', 'user_id');
        //return $this->belongsToMany(User::class, UserProduct::class, 'product_id', 'user_id', 'id', 'id');
    }

    /**
     *
     */
    public function transactions(){
        return $this->hasMany(Transaction::class);
    }

}
