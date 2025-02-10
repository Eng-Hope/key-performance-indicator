<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Kpi extends Model
{
    protected $fillable = [
        'name',
        'measurement',
        'review_duration',
        'target',
        'weight',
        'created_by',
    ];

    
    public function users(): BelongsToMany{
        return $this->belongsToMany(
            User::class,
        'user_kpi', 'kpi_id', 'user_id')
        ->withPivot('review', 'actual')
        ->withTimestamps();
    }
}
