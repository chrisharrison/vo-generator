<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\Type\DefaultTypes;

use ChrisHarrison\VoGenerator\Definition\Definition;
use ChrisHarrison\VoGenerator\HasInternalProperties;
use ChrisHarrison\VoGenerator\Type\SimpleType;
use ChrisHarrison\VoGenerator\Type\Type;
use ChrisHarrison\VoGenerator\ValueObject;

final class CompositeType extends SimpleType implements Type
{
    public function handle(Definition $definition): Definition
    {
        return $definition->withMergedPayload([
            'template' => $definition->type(),
            'implements' => [ValueObject::class, HasInternalProperties::class],
            'properties' => array_map(function (array $property) {
                return [
                    'name' => $property['name'],
                    'propertyName' => $property['propertyName'],
                    'required' => $property['required'] ?? false,
                ];
            }, $definition->payload()['properties']),
        ]);
    }
}
