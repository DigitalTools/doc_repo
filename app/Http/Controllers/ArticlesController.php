<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;
use Illuminate\Support\Facades\Auth;
use Google\Cloud\NaturalLanguage\NaturalLanguageClient;
use Google\Cloud\NaturalLanguage\Annotation;

class ArticlesController extends Controller
{

    public function register()
    {
        return view('articles.register');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
                'title'     => 'required',
                'body'   => 'required'
            ]);

            $article = new Article([
                'title'     => $request->input('title'),
                'user_id'   => Auth::user()->id,
                'body'   => $request->input('body')
            ]);

            $article->save();

            return redirect()->back()->with("status", "Article \"$article->title\" has been registered.");
    }

    public function articles()
    {
        $articles = Article::where('user_id', Auth::user()->id)->paginate(10);

        return view('articles.articles', compact('articles'));
    }

    public function show($id)
    {
        $article = Article::where('id', $id)->firstOrFail();
        /*
        # Your Google Cloud Platform project ID
        $projectId = 'npl-007';

        # Instantiates a client
        $language = new NaturalLanguageClient([
            'projectId' => $projectId
        ]);

        # The text to analyze
        $text = $article->body;

        # Detects the sentiment of the text
        $annotation = $language->analyzeSentiment($text);
        $sentiment = $annotation->sentiment();
        */
        $sentiment = 0;
        return view('articles.show', compact('article', 'sentiment'));
    }

}