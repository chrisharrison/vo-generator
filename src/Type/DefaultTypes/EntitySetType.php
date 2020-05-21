<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\Type\DefaultTypes;

use ChrisHarrison\VoGenerator\Definition\Definition;
use ChrisHarrison\VoGenerator\Definition\DefinitionName;
use ChrisHarrison\VoGenerator\EntitySet;
use ChrisHarrison\VoGenerator\HasInternalProperties;
use ChrisHarrison\VoGenerator\InternalEvaluator\InternalEvaluator;
use ChrisHarrison\VoGenerator\Type\SimpleType;
use ChrisHarrison\VoGenerator\Type\Type;
use ChrisHarrison\VoGenerator\ValueObject;
use Countable;

final class EntitySetType extends SimpleType implements Type
{
    private $internalEvaluator;

    public function __construct(
        InternalEvaluator $internalEvaluator
    ) {
        $this->internalEvaluator = $internalEvaluator;
    }

    public function handle(Definition $definition): Definition
    {
        return $definition->withMergedPayload([
            'template' => 'set',
            'implements' => [ValueObject::class, Countable::class, EntitySet::class],
            'holds' => $definition->payload()['holds'],
            'innerProperties' => $this->innerProperties($definition->payload()['holds']),
        ]);
    }

    private function innerProperties($innerType): array
    {
        $className = $this->internalEvaluator->evaluate(new DefinitionName($innerType));
        if (!is_a($className, HasInternalProperties::class, true)) {
            return [];
        }
        return $className::properties();
    }
}
