<?php

namespace App\Models\Cart;

use App\Models\Product\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity'
    ];

    /**
     * Relasi dengan model User.
     * Keranjang dimiliki satu User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi dengan model Product.
     * Keranjang miliki satu Product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope untuk memfilter order berdasarkan quantity.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $quantity
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByQuantity($query, $quantity)
    {
        return $query->where('quantity', $quantity);
    }
}
