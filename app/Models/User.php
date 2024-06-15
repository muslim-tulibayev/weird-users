<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function relationships()
    {
        return $this->belongsToMany(User::class, 'user_users', 'user_id', 'related_user_id')
            ->withPivot('type')
            ->withTimestamps();
    }

    public function relationshipsOfType($type)
    {
        return $this->relationships()->wherePivot('type', $type);
    }
}
