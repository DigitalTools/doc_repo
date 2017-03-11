<?php

namespace App\Http\Controllers\Source;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Article;
use Illuminate\Support\Facades\Log;

class ListController extends Controller
{

    public function input()
    {
        return view('urls.input');
    }

    public function parse(Request $request)
    {
        $this->validate($request, [
            'url'     => 'required',
        ]);

        $url = $request->input('url');

        $html = file_get_contents($url);

        $doc = new \DOMDocument();

        libxml_use_internal_errors(TRUE); //disable libxml errors

        if(!empty($html)){ //if any html is actually returned

            $doc->loadHTML($html);
            libxml_clear_errors(); //remove errors for yucky html

            $xpath = new \DOMXPath($doc);

            //get all the h2's with an id
            //$row = $xpath->query('//*[@id="wrapper"]/section[2]/div/section/article/div[1]/h2/a/@href');
            $row = $xpath->query('/html/body/div[3]/div/section/div/article/div[2]/div/a/@href');

            if($row->length > 0){
                foreach($row as $row){
                    $article_url = $row->nodeValue;
                    $error_message = 'Parsing: '.$article_url;
                    Log::info($error_message);
                    $this->parseArticleRMP($article_url);
                    //var_dump($row);
                }
            }
        }

        //return view('articles.show', compact('article'));
        return redirect()->back()->with("status", "URL \"$url\" has been parsed.");
    }


    public function parseArticleRMP($url){
        //var_dump($url);

        $article = Article::where('url', $url)->first();

        //if (is_null($article)) {

            $html = file_get_contents($url);

            $doc = new \DOMDocument();

            libxml_use_internal_errors(TRUE); //disable libxml errors

            if(!empty($html)){ //if any html is actually returned

                $doc->loadHTML($html);
                libxml_clear_errors(); //remove errors for yucky html

                $xpath = new \DOMXPath($doc);

                $date_text = '';
                foreach ($xpath->query("//meta[@property='article:published_time']") as $el) {
                    $date_text = $el->getAttribute("content");
                }
                $T_pos = strpos($date_text, 'T');
                $date_text = substr($date_text, 0, $T_pos);
                $published_on = date($date_text);

                $row = $xpath->query('/html/body/div[3]/div/article');

                if($row->length > 0){

                    foreach ($row as $n)
                    {
                        $title = $xpath->query('h2', $n)->item(0)->nodeValue;
                        
                        $body = $xpath->query('/html/body/div[3]/div/article/div', $n)->item(0)->nodeValue;
                        $C_pos = strpos($body, 'Columna publicada');
                        if ($C_pos) {
                            $body = substr($body, 0, $C_pos);
                        }

                        //$body = str_replace('eplAD4M("InlinePrg");', '', $body);
                        
                        $author_id = 1;

                        /*$article = new Article([
                            'title'     => $title,
                            'user_id'   => Auth::user()->id,
                            'body'   => $body,
                            'author_id'   => $author_id,
                            'published_on' => $published_on,
                            'url' => $url
                        ]);*/
                        $article->body = $body;

                        $error_message = 'Saving: '.$title;
                        Log::info($error_message);
                        $article->save();

                    }
                    
                }
            }

        //}

    }




    private function parseArticle($url){
        echo $url . "<br/>";
        dd();//var_dump($url);

        $article = Article::where('url', $url)->first();

        if (is_null($article)) {

            $html = file_get_contents($url);

            $doc = new \DOMDocument();

            libxml_use_internal_errors(TRUE); //disable libxml errors

            if(!empty($html)){ //if any html is actually returned

                $doc->loadHTML($html);
                libxml_clear_errors(); //remove errors for yucky html

                $xpath = new \DOMXPath($doc);

                $row = $xpath->query('//*[@id="ec-container"]/section/article');

                if($row->length > 0){

                    foreach ($row as $n)
                    {
                        $title = $xpath->query('h1', $n)->item(0)->nodeValue;
                        $title = str_replace(', por Alfredo Bullard', '', $title);

                        $date_text = $xpath->query('//*[@id="ec-container"]/section/article/time/@datetime', $n)->item(0)->nodeValue;
                        $T_pos = strpos($date_text, 'T');
                        $date_text = substr($date_text, 0, $T_pos);
                        $published_on = date($date_text);

                        $body = $xpath->query('//*[@id="nota-detalle"]', $n)->item(0)->nodeValue;
                        $body = str_replace('eplAD4M("InlinePrg");', '', $body);
                        
                        $author_id = 2;

                        $article = new Article([
                            'title'     => $title,
                            'user_id'   => Auth::user()->id,
                            'body'   => $body,
                            'author_id'   => $author_id,
                            'published_on' => $published_on,
                            'url' => $url
                        ]);

                        $article->save();

                    }
                    
                }
            }

        }


    }

}
