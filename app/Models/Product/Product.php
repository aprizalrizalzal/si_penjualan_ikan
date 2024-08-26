<?php

namespace App\Models\Product;

use App\Models\Cart\Cart;
use App\Models\Order\OrderItem;
use App\Models\Seller\Seller;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
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
     * Relasi dengan model OrderItem.
     * Product miliki banyak OrderItem.
     */
    public function seller()
    {
        return $this->belongsTo(Seller::class);
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
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Scope untuk memfilter order berdasarkan name.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $name
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByName($query, $name)
    {
        return $query->where('name', $name);
    }
}
