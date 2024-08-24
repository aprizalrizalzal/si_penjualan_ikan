<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentImage extends Model
{
    use HasFactory;

    protected $fillable = ['payment_id', 'image', 'alt'];

    /**
     * Relasi dengan model PaymentImage.
     * Payment miliki satu PaymentImage.
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
