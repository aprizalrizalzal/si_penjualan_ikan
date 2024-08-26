<?php

namespace App\Models\Seller;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'address', 'phone'];

    /**
     * Relasi dengan model User.
     * User milik satu User.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Relasi dengan model User.
     * User milik satu User.
     */
    public function sellerImage()
    {
        return $this->hasOne(SellerImage::class);
    }
}
