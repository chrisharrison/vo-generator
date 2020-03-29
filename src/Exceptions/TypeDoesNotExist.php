<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\Exceptions;

use RuntimeException;

use function sprintf;

final class TypeDoesNotExist extends RuntimeException
{
    public function __construct(string $typeName)
    {
        parent::__construct(sprintf('Type: %s does not exist', $typeName));
    }
}
