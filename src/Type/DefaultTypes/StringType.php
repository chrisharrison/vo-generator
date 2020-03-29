<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\Type\DefaultTypes;

use ChrisHarrison\VoGenerator\Definition\Definition;
use ChrisHarrison\VoGenerator\Type\SimpleType;
use ChrisHarrison\VoGenerator\Type\Type;

final class StringType extends SimpleType implements Type
{
    public function name(): string
    {
        return 'string';
    }

    public function enrichProperties(Definition $definition): array
    {
        return [];
    }
}
