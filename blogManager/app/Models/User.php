<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
    ];

    public function articles()
    {
        return $this->hasMany(Articles::class, 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comments::class, 'user_id');
    }
}
