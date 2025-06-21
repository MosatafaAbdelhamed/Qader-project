<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    //
    protected $fillable = [
        'volunteer_id', 'title', 'description', 'location', 'phone', 'urgency'
    ];

    public function volunteer()
    {
        return $this->belongsTo(Volunteer::class, 'volunteer_id')->select('volunteer_id', 'phone_number');
    }



}
