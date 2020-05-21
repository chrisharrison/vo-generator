<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\Type\DefaultTypes;

use ChrisHarrison\VoGenerator\Definition\Definition;
use ChrisHarrison\VoGenerator\Entity;
use ChrisHarrison\VoGenerator\Type\SimpleType;
use ChrisHarrison\VoGenerator\Type\Type;
use ChrisHarrison\VoGenerator\ValueObject;

final class EntityType extends SimpleType implements Type
{
    public function handle(Definition $definition): Definition
    {
        return $definition->withMergedPayload([
            'template' => 'composite',
            'implements' => [ValueObject::class, Entity::class],
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
