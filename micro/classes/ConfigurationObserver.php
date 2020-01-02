<?php

declare(strict_types=1);

namespace µ;

use Gestalt\Util\Observable;
use Gestalt\Util\ObserverInterface;
use League\BooBoo\Formatter\AbstractFormatter;
use League\BooBoo\Formatter\CommandLineFormatter;
use League\BooBoo\Formatter\HtmlFormatter;
use League\BooBoo\Formatter\HtmlTableFormatter;
use League\BooBoo\Formatter\JsonFormatter;
use League\BooBoo\Formatter\NullFormatter;
use mzdr\OhSnap\Formatter\PrettyFormatter;

class ConfigurationObserver implements ObserverInterface
{
    /**
     * List of supported error formatters.
     *
     * @var array
     */
    private array $formatters = [
        'html'       => HtmlFormatter::class,
        'html_table' => HtmlTableFormatter::class,
        'json'       => JsonFormatter::class,
        'cmd'        => CommandLineFormatter::class,
        'null'       => NullFormatter::class,
        'ohsnap'     => PrettyFormatter::class
    ];

    /**
     * Currently set formatter.
     *
     * @var AbstractFormatter|null
     */
    private ?AbstractFormatter $currentFormatter = null;

    /**
     * The update function that is getting called once the observer
     * receives an update.
     *
     * @param Observable $config
     */
    public function update(Observable $config): void
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

        // If micro is being run in command line interface mode,
        // it’s already set up for perfect formatting.
        //
        // If you really need to adjust formatters in CLI mode, do it
        // manually and directly on the BooBoo instance via error() calls.
        if (php_sapi_name() === 'cli') {
            return;
        }

        $this->currentFormatter = $error->formatter;

        // Setting up BooBoo via configuration files is currently
        // rather “basic”. This means you cannot set up certain
        // formatters for particular types of errors. If you need
        // advanced possibilities you should configure it customly
        // via error() calls.
        error()
            ->clearFormatters()
            ->pushFormatter(
                new $this->formatters[$error->formatter]($error)
            );
    }
}
