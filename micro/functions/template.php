<?php

namespace µ;

use League\Plates\Engine;
use League\Plates\Extension\Asset;
use Webmozart\PathUtil\Path;

/**
 * Provides access to the Plates template engine.
 *
 * @return Engine
 * @see http://platesphp.com/
 */
function template(): Engine
{
    static $plates;

    if ($plates instanceof Engine === true) {
        return $plates;
    }

    // By default we use the current working directory
    $cwd = getcwd();

    // See if any paths are provided by the configuration
    $paths = config()->get('µ.paths');

    // Set default path for views directory…
    $defaultViewsPath = Path::canonicalize((is_string($cwd) ? $cwd : '') . '/../views');

    // Create Plates instance and prefer the configured views path,
    // otherwise take the default path as fallback
    $plates = new Engine($paths->views ?? $defaultViewsPath);

    // If an assets path is given, also load the asset extension
    // @see http://platesphp.com/v3/extensions/asset/
    if (isset($paths->assets)) {
        $plates->loadExtension(
            new Asset($paths->assets, true)
        );
    }

    return $plates;
}
