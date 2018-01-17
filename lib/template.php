<?php

namespace µ;

use \League\Plates\Engine as Plates;
use \League\Plates\Extension\Asset;

function template(): Plates {
    static $plates;

    if ($plates instanceof Plates === false) {
        $defaultDirectory = join(DIRECTORY_SEPARATOR, [getcwd(), 'views']);
        $assetsPath = config('paths.assets');

        if (is_readable($defaultDirectory)) {
            $plates = new Plates($defaultDirectory);
        } else {
            $plates = new Plates(config('paths.views'));
        }

        if ($assetsPath) {
            $plates->loadExtension(
                new Asset($assetsPath, true)
            );
        }
    }

    return $plates;
};
