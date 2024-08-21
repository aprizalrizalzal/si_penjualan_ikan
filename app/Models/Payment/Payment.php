<?php

namespace App\Models\Payment;

use App\Models\Order\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
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
}
