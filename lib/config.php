<?php

namespace µ;

use \Adbar\Dot;

function config() {
    static $config;

    if ($config instanceof Dot === false) {
        $config = new Dot;
    }

    $args = func_get_args();
    $count = count($args);

    if ($count === 0) {
        return $config;
    }

    if ($count === 2) {
        $config[$args[0]] = $args[1];

        return;
    }

    if (is_array($args[0])) {
        foreach ($args[0] as $key => $value) {
            config($key, $value);
        }

        return;
    }

    return $config[$args[0]];
}
