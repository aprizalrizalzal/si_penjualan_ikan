<?php

namespace App\Models\Role;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

    /**
     * Relasi dengan model User.
     * Role milik satu banyak User.
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
