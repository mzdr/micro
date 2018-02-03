<?php

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

    if ($plates instanceof Engine === false) {
        $defaultDirectory = join(DIRECTORY_SEPARATOR, [getcwd(), 'views']);
        $assetsPath = config()->get('paths.assets');

        if (is_readable($defaultDirectory)) {
            $plates = new Engine($defaultDirectory);
        } else {
            $plates = new Engine(config()->get('paths.views'));
        }

        if ($assetsPath) {
            $plates->loadExtension(
                new Asset($assetsPath, true)
            );
        }
    }

    return $plates;
};
