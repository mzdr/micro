<?php

namespace µ;

use League\BooBoo\BooBoo;

/**
 * Custom error handler for PHP that allows for the execution of handlers
 * and formatters for viewing and managing errors in development and production.
 *
 * @param bool $reset Resets the current error handler.
 * @return BooBoo
 * @see https://github.com/thephpleague/booboo/
 */
function error($reset = false): BooBoo {
    static $eh;
    static $formatters = [
        'html' => '\League\BooBoo\Formatter\HtmlFormatter',
        'html_table' => '\League\BooBoo\Formatter\HtmlTableFormatter',
        'json' => '\League\BooBoo\Formatter\JsonFormatter',
        'cmd' => '\League\BooBoo\Formatter\CommandLineFormatter',
        'null' => '\League\BooBoo\Formatter\NullFormatter'
    ];

    if ($eh instanceof BooBoo === false) {
        $eh = new BooBoo([]);
    }

    if ($reset || empty($eh->getFormatters())) {
        $eh->clearFormatters()
           ->pushFormatter(new $formatters[config('error.formatter') ?? 'html_table'])
           ->register();
    }

    return $eh;
}

error();
