<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ParseAtlantic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:atlantic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse atlantic article';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //$this->info('info');
        //$this->error('error');
        //$this->line('line');
        $filename = 'engineers.txt';
        $url = 'https://www.theatlantic.com/technology/archive/2015/11/programmers-should-not-call-themselves-engineers/414271/';
        $raw_content = $this->parse($url);
        file_put_contents($filename, $raw_content);
        $this->info('Content is now in file');
    }

    private function parse($url)
    {

        $show_info = false;
        $raw_content = '';

        $opts = array('http'=>array('header' => "User-Agent:MyAgent/1.0\r\n"));
        $context = stream_context_create($opts);
        $html = file_get_contents($url, false, $context);

        $doc = new \DOMDocument();

        libxml_use_internal_errors(TRUE); //disable libxml errors

        if(!empty($html)){ //if any html is actually returned

            $doc->loadHTML($html);
            libxml_clear_errors(); //remove errors for yucky html

            $xpath = new \DOMXPath($doc);

            $date_text = '';
            $sections = $xpath->query("//section/@id[starts-with(., 'article-section')]/..");
            
            if($sections->length > 0){

                if ($show_info) {
                    $message = $sections->length . ' sections';
                    $this->info($message);
                };

                foreach ($sections as $section_key => $section) {

                    if ($show_info) {
                        $message = 'sections: ' . $section_key;
                        $this->info($message);
                    };
                    
                    $paragraphs = $xpath->query('p', $section);

                    if($paragraphs->length > 0){

                        if ($show_info) {
                            $message = $paragraphs->length . ' paragraphs';
                            $this->info($message);
                        };

                        foreach ($paragraphs as $p_key => $p) {
                          
                            if ($show_info) {
                                $message = 'paragraph: ' . $p_key;
                                $this->info($message);
                            };

                            //$this->line($p->nodeValue);
                            $raw_content .= $p->nodeValue . '\n';

                        };

                    }; // if($paragraphs->length > 0)

                }; // foreach ($sections

            }; // if($sections->length > 0)

        }; // if(!empty($html))

        return $raw_content;

    } // function parse($url)

}
