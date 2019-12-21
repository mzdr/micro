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

    // Create Plates instance and prefer the configured views path,
    // otherwise take the default path as fallback
    $plates = new Engine($paths->views ?? $cwd);

    // If folders are given by configuration file, add all of them automatically.
    // @see http://platesphp.com/v3/engine/folders/
    if (is_array($paths->folders)) {
        foreach ($paths->folders as $name => $folder) {
            $plates->addFolder($name, ...(array) $folder);
        }
    }

    // If an assets path is given, also load the asset extension
    // @see http://platesphp.com/v3/extensions/asset/
    if (isset($paths->assets)) {
        $plates->loadExtension(
            new Asset($paths->assets, true)
        );
    }

    return $plates;
}
