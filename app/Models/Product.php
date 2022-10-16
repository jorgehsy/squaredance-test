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

    public function scopeAvailable($query){
        return $query->where([
            ['status', Product::$ACTIVE],
            ['monthly_inventory', '>', 0]
        ]);
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

    private function reduceInventory(int $quantity = 1){
        $result = $this->monthly_inventory - $quantity;

        if ( $result < 0 )
            throw new Exception("There is no inventory to make this sale", 400);

        $this->update([
            'monthly_inventory' => $this->monthly_inventory - $quantity,
            'status' => $result === 0 ? Product::$EXPIRED : $this->status
        ]);

        return $this;
    }

    public function hasAvailableInventory(){
        return $this->monthly_inventory > 0;
    }

    public function isAvailable(){
        return $this->status === Product::$ACTIVE && $this->hasAvailableInventory();
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

    public function sell(int $quantity = 1){
        if ( !$this->isAvailable() )
            throw new \Exception("Product expired", 400);

        if ( !$this->hasAvailableInventory() )
            throw new \Exception("Not inventory left", 400);

        $this->reduceInventory($quantity);

        return $this;
    }
}
