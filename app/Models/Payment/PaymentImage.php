<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentImage extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'image', 'alt'];

    /**
     * Relasi dengan model Category.
     * Product miliki satu Category.
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
