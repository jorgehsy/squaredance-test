<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'profit'
    ];

    public function getProfitAttribute(){
        return count($this->transactions);
    }

    /**
     * The products list that the user own
     */
    public function products(){
        //return $this->hasManyThrough(Product::class, UserProduct::class, 'user_id', 'product_id', 'id', 'id');
        return $this->belongsToMany(Product::class, UserProduct::class, 'user_id', 'product_id', 'id', 'id');
    }

    public function transactions(){
        return $this->hasMany(Transaction::class);
    }

    public static function randomUser(){
        return User::factory()
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
