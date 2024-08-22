<?php

namespace App\Models\Order;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];

    /**
     * Relasi dengan model Order.
     * OrderItem milik satu order.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relasi dengan model Product.
     * OrderItem mengacu pada satu produk.
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
