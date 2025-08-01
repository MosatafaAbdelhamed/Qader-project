<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Volunteer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'volunteer_id';

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'password',
        'img',
    ];

    protected $hidden = [
        'password',
    ];

    public function applications()
    {
    return $this->hasMany(\App\Models\Application::class, 'volunteer_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'volunteer_id');
    }


}
