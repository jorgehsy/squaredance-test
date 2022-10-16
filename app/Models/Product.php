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
    public function owner(){
        return $this->hasOneThrough(User::class, UserProduct::class, 'product_id', 'id', 'id', 'user_id');
    }

    /**
     * Return de transactions made for this product
     */
    public function transactions(){
        return $this->hasMany(Transaction::class);
    }

    public function scopeActive($query){
        return $query->where('status', Product::$ACTIVE);
    }

    public function scopeExpired($query){
        return $query->where('status', Product::$EXPIRED);
    }

    public function scopeOnHold($query){
        return $query->where('status', Product::$ONHOLD);
    }

    private function changeStatus(string $status){
        $this->update([
            'status' => $status
        ]);

        return $this;
    }

    public function isAvailable(){
        return $this->status === Product::$ACTIVE;
    }

    public function activate(){
        $this->changeStatus(Product::$ACTIVE);

        return $this;
    }

    public function expire(){
        $this->changeStatus(Product::$EXPIRED);

        return $this;
    }

    public function approve(){
        $this->changeStatus(Product::$ACTIVE)
        ->owner()
        ->update(
            ['status' => UserProduct::$APPROVED]
        );

        return $this;
    }

    public function reject(){
        $this->changeStatus(Product::$ONHOLD)
        ->owner()
        ->update(
            ['status' => UserProduct::$REJECTED]
        );

        return $this;
    }
}
