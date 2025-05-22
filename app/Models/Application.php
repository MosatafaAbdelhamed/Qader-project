<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Volunteer;
use App\Models\Opportunity;


class Application extends Model
{

    protected $primaryKey = 'application_id';

    protected $fillable = [
        'volunteer_id',
        'opportunity_id',
        'status',
        'date'
        ];

    public function volunteer()
    {
    return $this->belongsTo(Volunteer::class, 'volunteer_id');
    }


    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class, 'opportunity_id', 'opportunity_id');
    }
}
