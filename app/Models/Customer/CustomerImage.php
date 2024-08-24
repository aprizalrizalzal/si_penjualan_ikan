<?php

namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerImage extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'image', 'alt'];

    /**
     * Relasi dengan model Category.
     * Product miliki satu Category.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
