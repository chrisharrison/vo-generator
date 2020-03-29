<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\Exceptions;

use ChrisHarrison\VoGenerator\Definition\DefinitionName;
use RuntimeException;

final class DefinitionDoesNotExist extends RuntimeException
{
    public function __construct(DefinitionName $name)
    {
        parent::__construct(sprintf('There is no definition with the name: %s', $name->toString()));
    }
}
