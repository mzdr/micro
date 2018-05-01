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
            $error = $config->get('µ.error');
            $formatter = json_encode($error);

            // Do we have error formatting configuration and
            // if so, is the formatter supported?
            if (isset($error->formatter, $this->formatters[$error->formatter]) === false) {
                return;
            }

            // Given error settings do not differ from current settings…
            if ($formatter === $this->currentFormatter) {
                return;
            }

            $this->currentFormatter = $error->formatter;

            // Setting up BooBoo via configuration files is currently rather “basic”.
            // This means you cannot set up certain formatters for particular types of errors.
            // If you need advanced possibilities you should configure it customly via error() calls.
            error()->clearFormatters()
                   ->pushFormatter(
                       new $this->formatters[$error->formatter]($error)
                   );
        }
    }
);

error();
