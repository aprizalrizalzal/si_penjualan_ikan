<?php

namespace App\Models\Product;

use App\Models\Cart\Cart;
use App\Models\Cart\CartItem;
use App\Models\Order\OrderItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'weight',
        'category_id'
    ];

    /**
     * Relasi dengan model Category.
     * Product miliki satu Category.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi dengan model ProductImage.
     * Product miliki banyak ProductImage.
     */
    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Relasi dengan model Cart.
     * Product miliki banyak Cart.
     */
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Relasi dengan model OrderItem.
     * Product miliki banyak OrderItem.
     */
    public function orderItems()
    {
        return $this->belongsTo(OrderItem::class);
    }

    /**
     * Scope untuk memfilter order berdasarkan status.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByName($query, $name)
    {
        return $query->where('name', $name);
    }
}
