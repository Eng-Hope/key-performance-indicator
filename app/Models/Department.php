<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    protected $fillable = [
        "name",
        "created_by",
    ];
    
    public function created_by(): BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function users(): HasMany{
        return $this->hasMany(User::class);
    }

}
