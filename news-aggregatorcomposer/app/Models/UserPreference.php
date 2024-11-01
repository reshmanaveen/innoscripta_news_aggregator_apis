<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    protected $fillable = ['user_id', 'preferred_source', 'preferred_category','preferred_author'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
