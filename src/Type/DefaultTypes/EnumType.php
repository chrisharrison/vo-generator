<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\Type\DefaultTypes;

use ChrisHarrison\VoGenerator\Definition\Definition;
use ChrisHarrison\VoGenerator\Type\SimpleType;
use ChrisHarrison\VoGenerator\Type\Type;

final class EnumType extends SimpleType implements Type
{
    public function handle(Definition $definition): Definition
    {
        return $definition->withMergedPayload([
            'template' => $definition->type(),
            'values' => array_map(function (string $value) {
                return strtoupper($value);
            }, $definition->payload()['values']),
        ]);
    }
}
