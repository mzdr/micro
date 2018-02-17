<?php

namespace µ;

use InvalidArgumentException;
use Medoo\Medoo;

/**
 * Provides access to the Medoo database package.
 *
 * @return Medoo
 * @see https://github.com/catfan/Medoo
 */
function db(): Medoo
{
    static $db;

    if ($db instanceof Medoo === false) {
        $config = config()->get('db', null, true);

        if ($config === null) {
            throw new InvalidArgumentException(
                "No database configuration has been found. Use µ\config()->set('db', '…') to provide configuration. See Medoo documentation for additional details."
            );
        }

        $db = new Medoo($config);
    }

    return $db;
}

/**
 * @see \µ\db()
 */
function database(): Medoo
{
    return db();
}
