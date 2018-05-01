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
            'null'       => '\League\BooBoo\Formatter\NullFormatter',
            'ohsnap'     => '\mzdr\OhSnap\Formatter\PrettyFormatter'
        ];

        private $currentFormatter = null;

        public function update(Observable $config)
        {
            $error = config()->get('µ.error');

            if (isset($this->formatters[$error->formatter]) === false || $error->formatter === $this->currentFormatter) {
                return;
            }

            $this->currentFormatter = $error->formatter;

            error()->clearFormatters()
                   ->pushFormatter(
                       new $this->formatters[$error->formatter]($error)
                   );
        }
    }
);

error();
