<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Author;
use Illuminate\Support\Facades\DB;

class AuthorsController extends Controller
{

    public function show($id)
    {

        $author = Author::find($id);
        $articles = $author->articles()->get();
        /*
        $filename = $author->alias . '.csv';

        $fp = fopen($filename, 'w');

        foreach ($articles as $key => $article) {
            $record = [
              $article->score,
              $article->magnitude,
              strlen($article->body)
            ];
            fputcsv($fp, $record);
        };

        fclose($fp);
        */

        $mag_stddev = DB::table('articles')
                     ->select(DB::raw('stddev(magnitude) as stddev'))
                     ->where('author_id', '=', $id)
                     ->get();

        $score_stddev = DB::table('articles')
                     ->select(DB::raw('stddev(score) as stddev'))
                     ->where('author_id', '=', $id)
                     ->get();

        $stats = array(
          'score' => array(
            'max' => $articles->max('score'),
            'min' => $articles->min('score'),
            'avg' => $articles->avg('score'),
            'stddev' => $score_stddev[0]->stddev
          ),
          'magnitude' => array(
            'max' => $articles->max('magnitude'),
            'min' => $articles->min('magnitude'),
            'avg' => $articles->avg('magnitude'),
            'stddev' => $mag_stddev[0]->stddev
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

        $minMag = $author
          ->articles()
          ->orderBy('magnitude')
          ->take(5)
          ->get();

        $maxMag = $author
          ->articles()
          ->orderBy('magnitude', 'desc')
          ->take(5)
          ->get();

        $rank = array(
          'minScored' => $minScored,
          'maxScored' => $maxScored,
          'minMag' => $minMag,
          'maxMag' => $maxMag
        );

        return view('authors.show', compact('author', 'stats', 'articles', 'rank'));

    }

}
