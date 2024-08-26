<?php

namespace App\Models\Seller;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerImage extends Model
{
    use HasFactory;

    protected $fillable = ['seller_id', 'image', 'alt'];

    /**
     * Relasi dengan model Category.
     * Product miliki satu Category.
     */
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }
}
