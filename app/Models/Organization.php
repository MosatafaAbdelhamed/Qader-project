<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Organization extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $table = 'organizations';
    protected $primaryKey = 'organization_id';

    protected $fillable = [
        'name',
        'email',
        'location',
        'img',
        'phone',
        'password',
    ];

    public $timestamps = true;

    protected $hidden = [
        'password',
    ];
}
