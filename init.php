<?php

namespace µ;

use Gestalt\Util\ObserverInterface;
use Gestalt\Util\Observable;

config()->attach(
    new class implements ObserverInterface {
        private $formatters = [
            'html'       => '\League\BooBoo\Formatter\HtmlFormatter',
            'html_table' => '\League\BooBoo\Formatter\HtmlTableFormatter',
            'json'       => '\League\BooBoo\Formatter\JsonFormatter',
            'cmd'        => '\League\BooBoo\Formatter\CommandLineFormatter',
            'null'       => '\League\BooBoo\Formatter\NullFormatter'
        ];

        private $currentFormatter = null;

        public function update(Observable $config)
        {
            $formatter = config()->get('µ.error.formatter');

            if (isset($this->formatters[$formatter]) === false || $formatter === $this->currentFormatter) {
                return;
            }

            $this->currentFormatter = $formatter;

            error()->clearFormatters()
                   ->pushFormatter(
                       new $this->formatters[$formatter]
                   );
        }
    }
);

error();
