<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    
    protected $fillable = [
        'name', 'alias'
    ];

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function stats($id)
    {

        

    }

}
