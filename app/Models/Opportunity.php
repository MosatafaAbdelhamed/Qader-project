<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opportunity extends Model
{
    //

use HasFactory;

    protected $fillable = ['title', 'description', 'category_id', 'user_id'];

    // relationship users (الفرصة يمكن أن تحتوي على العديد من المتطوعين)
    public function users()
    {
        return $this->belongsToMany(User::class, 'opportunity_user');
    }
}
