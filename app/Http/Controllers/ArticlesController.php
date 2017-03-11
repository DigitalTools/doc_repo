<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;
use App\Author;
use Illuminate\Support\Facades\Auth;
use Google\Cloud\NaturalLanguage\NaturalLanguageClient;
use Google\Cloud\NaturalLanguage\Annotation;
use Illuminate\Support\Facades\Log;

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
            'author'   => 'required',
            'url'   => 'required'
        ]);

        $article = new Article([
            'title'     => $request->input('title'),
            'user_id'   => Auth::user()->id,
            'body'   => $request->input('body'),
            'author_id'   => $request->input('author'),
            'url'   => $request->input('url')
        ]);

        $article->save();

        return redirect()->back()->with("status", "Article \"$article->title\" has been registered.");
    }

    public function articles()
    {
        $articles = Article::where('user_id', Auth::user()->id)->paginate(10);
        //$articles = Article::where('user_id', Auth::user()->id)->where('id', 20)->paginate(10);
        //$this->updateSentiment($articles);
        $authors = Author::all();

        return view('articles.articles', compact('articles', 'authors'));
    }

    public function articlesByAuthor($author_id)
    {
        $author = Author::find($author_id);
        $articles = $author->articles()->paginate(10);
        //$articles = $author->articles()->where('score', NULL)->get();
        //$this->updateSentiment($articles);
        return view('articles.articlesbyAuthor', compact('author', 'articles'));
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
            
            if ( !empty($article->body) && $article->magnitude == 0 ) {
                $title = $article->title;
                $error_message = 'Updating score to: '.$title;
                Log::info($error_message);

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

        if (empty($article->body)) {
            $listController = new Source\ListController();
            $listController->parseArticleRMP($article->url);
        };

        return view('articles.show', compact('article'));
    }

    public function destroy($id)
    {
        #$affectedRows = Article::where('id', $id)->delete();
        $article = Article::findOrFail($id);
        $article->delete();
        //'Task successfully deleted!'
        return redirect()->route('articles.index')->with("status", "Article \"$article->title\" has been deleted.");
    }

    public function edit($id)
    {
        $article = Article::where('id', $id)->firstOrFail();
        $authors = Author::all();
        return view('articles.edit', compact('article', 'authors'));
    }

    public function update($id, Request $request)
    {
        $article = Article::where('id', $id)->firstOrFail();
        $article->url = $request->input('url');
        $article->save();
        return view('articles.show', compact('article'));
    }

}