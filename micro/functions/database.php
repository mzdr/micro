<?php

declare(strict_types=1);

namespace µ;

use RuntimeException;

/**
 * Provides access to a simple wrapper class for
 * Doctrine’s DBAL and ORM.
 *
 * @return Database
 * @see https://www.doctrine-project.org/
 */
function db(): Database
{
    static $db;

    if ($db instanceof Database === true) {
        return $db;
    }

    // Retrieve database configuration…
    $config = config()->get('µ.db');

    // If no configuration was found but this function was called,
    // throw an RuntimeException and let the user know why things went wrong.
    if ($config === null) {
        throw new RuntimeException(
            "No database configuration has been found. Use µ\config()->set('µ.db', []) to provide configuration. See Doctrine/DBAL documentation for additional details."
        );
    }

    // Use wrapper class to ease access to Doctrine’s DBAL/ORM…
    return $db = new Database($config, config()->get('µ.env'));
}

/**
 * Just an alias of µ\db().
 *
 * @see db
 */
function database(): Database
{
    return db();
}
