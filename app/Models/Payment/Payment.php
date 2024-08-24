<?php

namespace App\Models\Payment;

use App\Models\Order\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_code',
        'order_id',
        'amount',
        'payment_method',
        'status',
    ];

    /**
     * Relasi ke tabel Order.
     * Satu pembayaran terkait dengan satu pesanan.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relasi ke tabel Payments.
     * Satu pembayaran miliki banyak PaymentImages.
     */
    public function paymentImages()
    {
        return $this->belongsTo(PaymentImage::class);
    }

    /**
     * Scope untuk memfilter order berdasarkan status.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
