<?php

namespace µ;

const VERSION = '3.0.0';

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
