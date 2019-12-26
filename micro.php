<?php

namespace µ;

define('VERSION', json_decode(file_get_contents(__DIR__ . '/composer.json'))->version);

foreach ([
    'cache',
    'config',
    'database',
    'error',
    'router',
    'template',
    'validator'
] as $file) {
    require_once __DIR__ . "/micro/functions/$file.php";
}

require_once __DIR__ . '/init.php';
