<?php

namespace MSA\LaravelGrapes\Services;

// use simplehtmldom\HtmlDocument;
use voku\helper\HtmlDomParser;

class GenerateFrontEndService {

    private $langs;

    public function __construct()
    {
        $this->langs = config('lg.languages');
    }

    public function htmlInitialization($html_string, $slug)
    {
        $html = new HtmlDomParser();

        $html->load($html_string);

        $text_blocks = $html->find('text');

        $this->formatingAuthShortcodes($html);
        $this->formatingCsrf($html);

        // for pro version
        // $all_text = $this->formatingTextBlocks($text_blocks, $slug);

        $str = $html->save();
        return $str;
    }

    protected function formatingAuthShortcodes($html)
    {
        $auth_shortcodes = $html->find('[auth_shortcode]');

        foreach ($auth_shortcodes as $auth_shortcode) {

            $code = $auth_shortcode->getAttribute('auth_shortcode');

            $guard = $auth_shortcode->getAttribute('guard');


            $original_text = $auth_shortcode->text();

            if ($code === '[auth_email_shortcode]') {
                $auth_shortcode->nodeValue = ' @if(auth("'.$guard.'")->user()) {{auth()->user()->email}} @else '.$original_text.' @endif ';
            }

            if ($code === '[auth_link_shortcode]') {
                $auth_shortcode->nodeValue = ' @if(auth("'.$guard.'")->user()) '.$auth_shortcode.' @endif ';
            }

            if ($code === '[none_auth_link_shortcode]') {
                $auth_shortcode->nodeValue = ' @if(!auth("'.$guard.'")->user()) '.$auth_shortcode.' @endif ';
            }

            $auth_shortcode->removeAttribute('guard');

            $auth_shortcode->removeAttribute('auth_shortcode');
        }
    }

    protected function formatingCsrf($html)
    {
        $comments =  $html->find('comment');

        foreach ($comments as $key => $value) {

            if($value->innertext === '- @csrf -')
            {
                $value->outertext = str_replace(['<!--- ', ' --->'], '',$value->outertext);
            }
        }
    }

    // for pro version
    protected function formatingTextBlocks($text_blocks, $page_name)
    {
        $trans_content = [];

        foreach ($text_blocks as $block) {

            $text = $block->text();

            if (! array_key_exists('en', $trans_content)) {
                $trans_content['en'] = [];
            }

            $trans_content['en'][$text] = $text;

            $block->innertext  = '{{__("'.$page_name.'.'.$text.'")}}';

            foreach ($this->langs as $lang) {

                $lang_attr = $block->getAttribute($lang);

                if (! array_key_exists($lang,  $trans_content)) {
                    $trans_content[$lang] = [];
                }

                if ($lang_attr) {
                    $trans_content[$lang][$text] = $lang_attr;
                }
                else {
                    $trans_content[$lang][$text] = $text;
                }
            }
        }

        $this->generateLanguageFile($trans_content, $page_name);
    }

    // for pro version
    protected function generateLanguageFile($trans_content, $page_name)
    {

        foreach ($trans_content as $lang => $value) {

            $lang_path = lang_path($lang);

            if (! is_dir($lang_path)) {
                mkdir($lang_path);
            }

            $lang_file = $lang_path.'/'.$page_name.'.php';

            $lang_target_file = fopen($lang_file, 'w');

            fwrite($lang_target_file, "<?php \n\n\nreturn [\n    ".var_export($trans_content[$lang], true)."\n];");

            fclose($lang_target_file);

            file_put_contents($lang_file, str_replace(
                'array (',
                '',
                file_get_contents($lang_file)
            ));

            file_put_contents($lang_file, str_replace(
                ')',
                '',
                file_get_contents($lang_file)
            ));
        }
    }

}
