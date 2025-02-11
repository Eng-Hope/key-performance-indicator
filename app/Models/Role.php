<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Role extends Model
{

    protected $fillable = ['user_id', 'role'];
    public $timestamps = true;
    public $incrementing = false; // Because user_id is the primary key

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
}
