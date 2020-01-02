<?php

declare(strict_types=1);

namespace µ;

// This configuration observer is used for internal micro logic.
config()->attach(
    new ConfigurationObserver()
);

// Make sure the error handler is registered.
error();
