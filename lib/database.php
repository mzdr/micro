<?php

namespace µ;

use Medoo\Medoo;

/**
 * Provides access to the Medoo database package.
 *
 * @return Medoo
 * @see https://github.com/catfan/Medoo
 */
function db(): Medoo {
    static $db;

    if ($db instanceof Medoo === false) {
        $db = new Medoo(config('db'));
    }

    return $db;
}

/**
 * @see \µ\db
 */
function database(...$args): Medoo {
    return db(...$args);
}
