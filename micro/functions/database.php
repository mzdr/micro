<?php

namespace µ;

use InvalidArgumentException;
use Medoo\Medoo;

/**
 * Provides access to the Medoo database package.
 *
 * @return Medoo
 * @see https://github.com/catfan/Medoo
 * @throws InvalidArgumentException If called without database configuration.
 */
function db(): Medoo
{
    static $db;

    if ($db instanceof Medoo === true) {
        return $db;
    }

    $config = config()->get('µ.db', null, true);

    if ($config === null) {
        throw new InvalidArgumentException(
            "No database configuration has been found. Use µ\config()->set('µ.db', '…') to provide configuration. See Medoo documentation for additional details."
        );
    }

    return $db = new Medoo($config);
}

/**
 * @see db()
 */
function database(): Medoo
{
    return db();
}
