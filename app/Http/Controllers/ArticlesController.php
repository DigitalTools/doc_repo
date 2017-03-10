<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;
use App\Author;
use Illuminate\Support\Facades\Auth;
use Google\Cloud\NaturalLanguage\NaturalLanguageClient;
use Google\Cloud\NaturalLanguage\Annotation;
//use Illuminate\Support\Facades\Log;

class ArticlesController extends Controller
{

    public function register()
    {
        $authors = Author::all();
        return view('articles.register', compact('authors'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
                'title'     => 'required',
                'body'   => 'required',
                'author'   => 'required'
            ]);

            $article = new Article([
                'title'     => $request->input('title'),
                'user_id'   => Auth::user()->id,
                'body'   => $request->input('body'),
                'author_id'   => $request->input('author')
            ]);

            $article->save();

            return redirect()->back()->with("status", "Article \"$article->title\" has been registered.");
    }

    public function articles()
    {
        $articles = Article::where('user_id', Auth::user()->id)->paginate(10);
        //$this->updateSentiment($articles);
        $authors = Author::all();

        return view('articles.articles', compact('articles', 'authors'));
    }

    private function updateSentiment($articles)
    {

        # Your Google Cloud Platform project ID
        $projectId = 'npl-007';

        # Instantiates a client
        $language = new NaturalLanguageClient([
            'projectId' => $projectId
        ]);

        foreach ($articles as $key => $article) {
            
            if ( is_null($article->score) ) {
                //$title = $article->title;
                ///$error_message = 'Score updated to: '.$title;
                //Log::info($error_message);

                # The text to analyze
                $text = $article->body;

                # Detects the sentiment of the text
                $annotation = $language->analyzeSentiment($text);
                $sentiment = $annotation->sentiment();
                
                $score = $sentiment['score'];
                $magnitude = $sentiment['magnitude'];

                $article->score = $score;
                $article->magnitude = $magnitude;

                $article->save();

            }

        }

    }

    public function show($id)
    {
        $article = Article::where('id', $id)->firstOrFail();
        return view('articles.show', compact('article'));
    }

    public function destroy($id)
    {
        #$affectedRows = Article::where('id', $id)->delete();
        $article = Article::findOrFail($id);
        $article->delete();
        //'Task successfully deleted!'
        return redirect()->route('articles.index')->with("status", "Article \"$article->title\" has been deleted.");;
    }

}