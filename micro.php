<?php

namespace µ;

const VERSION = '2.1.0';

foreach (['cache', 'config', 'database', 'error', 'router', 'template'] as $file) {
    require_once __DIR__ . "/micro/functions/$file.php";
}

require_once __DIR__ . '/init.php';
