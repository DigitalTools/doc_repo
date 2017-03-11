<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Author;

class AuthorsController extends Controller
{

    public function show($id)
    {

        $author = Author::find($id);
        $articles = $author->articles()->get();

        $stats = array(
          'score' => array(
            'max' => $articles->max('score'),
            'min' => $articles->min('score'),
            'avg' => $articles->avg('score'),
          ),
          'magnitude' => array(
            'max' => $articles->max('magnitude'),
            'min' => $articles->min('magnitude'),
            'avg' => $articles->avg('magnitude'),
          )
        );

        $minScored = $author
          ->articles()
          ->orderBy('score')
          ->take(5)
          ->get();

        $maxScored = $author
          ->articles()
          ->orderBy('score', 'desc')
          ->take(5)
          ->get();

        return view('authors.show', compact('author', 'stats', 'articles', 'minScored', 'maxScored'));

    }

}
