<?php

# PHP Google Translate.
# https://github.com/nggit/php-google-translate
# Copyright (c) 2021 nggit.

namespace Nggit\Google;
use Nggit\PHPSimpleClient\Client;

class Translate
{
    protected $text;
    protected $key;
    protected $sl;
    protected $tl;
    protected $client;
    protected $halted = false;

    public function __construct($options = array('key' => '', 'lang' => array('auto' => 'en')), $text = '')
    {
        $this->text = $text;
        $this->key  = $options['key'];
        foreach ($options['lang'] as $source => $target) {
            $this->sl = $source;
            $this->tl = $target;
        }
    }

    public function process()
    {
        if ($this->halted || strlen($this->text) < 2) {
            return $this;
        }
        $headers = array(
            'User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:78.0) Gecko/20100101 Firefox/78.0',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Language: en-US,en;q=0.5',
            'Referer: https://translate.google.com/',
            'Content-Type: application/json'
        );
        $this->client = Client::create();
        $this->client->setHeaders($headers);
        $this->client->setUrl('https://translation.googleapis.com/language/translate/v2?key=' . $this->key);
        $data = array(
            'q'      => $this->text,
            'source' => $this->sl,
            'target' => $this->tl,
            'format' => 'text'
        );
        $this->client->request('POST', json_encode($data));
        $this->client->send();
        return $this->parse();
    }

    protected function parse()
    {
        $data = json_decode($this->client->getBody(), true);
        if (!empty($data['data']['translations'][0]['translatedText'])) {
            $this->text = $data['data']['translations'][0]['translatedText'];
        }
        return $this;
    }

    public function setText($text = '')
    {
        if ($text == $this->text) {
            $this->halted = true;
        } else {
            $this->halted = false;
            $this->text   = $text;
        }
        return $this;
    }

    public function setSource($source = 'auto')
    {
        if ($source != $this->sl) {
            $this->halted = false;
            $this->sl     = $source;
        }
        return $this;
    }

    public function setTarget($target = 'en')
    {
        if ($target != $this->tl) {
            $this->halted = false;
            $this->tl     = $target;
        }
        return $this;
    }

    public function getResults()
    {
        return $this->text;
    }
}
