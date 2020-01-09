<?php

declare(strict_types=1);

namespace µ;

use League\Plates\Engine;
use League\Plates\Extension\Asset;

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

    // See if any paths are provided by the configuration
    $paths = config()->get('µ.paths');

    // Create Plates instance and prefer the configured views path,
    // otherwise take the current working directory as fallback
    $plates = new Engine($paths->views ?? root());

    // If folders are given by configuration file, add all of them automatically.
    // @see http://platesphp.com/v3/engine/folders/
    if ($paths->folders) {
        foreach ($paths->folders as $name => $folder) {
            [$path, $fallback] = (array) $folder + ['', false];

            $plates->addFolder($name, root()->resolve($path), $fallback);
        }
    }

    // If an assets path is given, also load the asset extension
    // @see http://platesphp.com/v3/extensions/asset/
    if (isset($paths->assets)) {
        $plates->loadExtension(
            new Asset(root()->resolve($paths->assets), true)
        );
    }

    return $plates;
}
