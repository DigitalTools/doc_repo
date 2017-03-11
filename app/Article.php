<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    //

    protected $fillable = [
        'user_id', 'title', 'body', 'author_id', 'url', 'published_on'
    ];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

}
