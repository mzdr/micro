<?php

namespace µ;

use Gestalt\Util\ObserverInterface;
use Gestalt\Util\Observable;

$observer = new class implements ObserverInterface {
    private $formatters = [
        'html'       => '\League\BooBoo\Formatter\HtmlFormatter',
        'html_table' => '\League\BooBoo\Formatter\HtmlTableFormatter',
        'json'       => '\League\BooBoo\Formatter\JsonFormatter',
        'cmd'        => '\League\BooBoo\Formatter\CommandLineFormatter',
        'null'       => '\League\BooBoo\Formatter\NullFormatter'
    ];

    public function update(Observable $config)
    {
        $formatter = config()->get('error.formatter');

        if (isset($this->formatters[$formatter])) {
            error()->clearFormatters()
                   ->pushFormatter(new $this->formatters[$formatter]);
        }
    }
};

config()->attach($observer);

error();
