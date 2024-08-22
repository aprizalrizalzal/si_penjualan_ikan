<?php

namespace App\Models\Customer;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'address', 'phone'];

    /**
     * Relasi dengan model User.
     * User milik satu User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
