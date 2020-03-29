<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\Exceptions;

use RuntimeException;
use Throwable;

final class EvaledCodeFailure extends RuntimeException
{
    public function __construct(string $code, Throwable $previous)
    {
        parent::__construct(
            'There was an error when trying to eval code: ' . $code,
            0,
            $previous
        );
    }
}
