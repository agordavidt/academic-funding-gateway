<?php

namespace App\Models;

class EbulkSmsMessage
{
    public $content;
    public $from;

    public function content($content)
    {
        $this->content = $content;
        return $this;
    }

    public function from($from)
    {
        $this->from = $from;
        return $this;
    }
}