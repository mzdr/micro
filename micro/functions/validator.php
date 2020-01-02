<?php

declare(strict_types=1);

namespace µ;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Provides access to Symfony’s validation package.
 *
 * @return ValidatorInterface
 * @see https://symfony.com/doc/current/validation.html
 */
function validator(): ValidatorInterface
{
    /** @var ValidatorInterface $validator Validator instance. */
    static $validator;

    if ($validator instanceof ValidatorInterface === true) {
        return $validator;
    }

    $validator = Validation::createValidatorBuilder()
        ->enableAnnotationMapping()
        ->getValidator();

    return $validator;
}
