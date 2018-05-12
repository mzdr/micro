<?php

namespace µ;

/**
 * Provides access to the Gestalt configuration package.
 *
 * @return Configuration
 * @see https://github.com/samrap/gestalt
 */
function config(): Configuration
{
    static $config;

    if ($config instanceof Configuration === true) {
        return $config;
    }

    return $config = new Configuration();
}
