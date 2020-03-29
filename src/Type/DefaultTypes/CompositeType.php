<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\Type\DefaultTypes;

use ChrisHarrison\VoGenerator\Definition\Definition;
use ChrisHarrison\VoGenerator\Type\SimpleType;
use ChrisHarrison\VoGenerator\Type\Type;

final class CompositeType extends SimpleType implements Type
{
    public function name(): string
    {
        return 'composite';
    }

    public function enrichProperties(Definition $definition): array
    {
        return [
            'properties' => array_map(function (array $property) {
                return [
                    'name' => $property['name'],
                    'propertyName' => $property['propertyName'],
                    'required' => $property['required'] ?? false,
                ];
            }, $definition->additionalProperties()['properties']),
        ];
    }
}
