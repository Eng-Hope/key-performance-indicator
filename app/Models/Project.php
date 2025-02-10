<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'created_by',
        'description',
    ];

    public function users(): BelongsToMany{
        return $this->belongsToMany(
            User::class,
         'user_projects', 
         'project_id', 
         'user_id');
    }
}
