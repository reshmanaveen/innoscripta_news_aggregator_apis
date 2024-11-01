<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title',
        'description',
        'url',
        'url_to_image',
        'author',
        'source_name',
        'source_id',
        'published_at',
        'content',
        'category'
    ];
}
