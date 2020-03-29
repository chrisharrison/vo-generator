<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\Type\DefaultTypes;

use ChrisHarrison\VoGenerator\Definition\Definition;
use ChrisHarrison\VoGenerator\Type\SimpleType;
use ChrisHarrison\VoGenerator\Type\Type;

use function strtoupper;

final class EnumType extends SimpleType implements Type
{
    public function name(): string
    {
        return 'enum';
    }

    public function enrichProperties(Definition $definition): array
    {
        return [
            'values' => array_map(function (string $value) {
                return strtoupper($value);
            }, $definition->additionalProperties()['values']),
        ];
    }
}
