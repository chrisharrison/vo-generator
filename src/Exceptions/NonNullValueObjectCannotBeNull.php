<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\Exceptions;

use RuntimeException;

final class NonNullValueObjectCannotBeNull extends RuntimeException
{
    public function __construct(string $className)
    {
        parent::__construct(sprintf('Tried to instantiate a %s with null.', $className));
    }
}
