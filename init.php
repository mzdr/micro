<?php

namespace µ;

use Gestalt\Util\ObserverInterface;
use Gestalt\Util\Observable;

$observer = new class implements ObserverInterface {
    public function update(Observable $config) {
        error(true);
    }
};

config()->attach($observer);
