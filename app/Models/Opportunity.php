<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Application;

class Opportunity extends Model
{
    use HasFactory;

    protected $table = 'opportunities';
    protected $primaryKey = 'opportunity_id';

    protected $fillable = ['title', 'description', 'category_id', 'organization_id', 'start', 'end', 'hours'];

    public $timestamps = true;

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function organization()
    {
    return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function applications()
    {
    return $this->hasMany(Application::class, 'opportunity_id', 'opportunity_id');
    }
    public function reports()
    {
    return $this->hasMany(Report::class, 'opportunity_id');
    }

}
