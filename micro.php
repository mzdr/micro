<?php

namespace µ;

define('VERSION', json_decode(file_get_contents(__DIR__ . '/composer.json'))->version);

foreach (glob(__DIR__ . '/micro/functions/*.php') as $file) {
    require_once $file;
}

require_once __DIR__ . '/init.php';
