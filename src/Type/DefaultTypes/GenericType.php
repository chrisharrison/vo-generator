<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\Type\DefaultTypes;

use ChrisHarrison\VoGenerator\Definition\Definition;
use ChrisHarrison\VoGenerator\Type\Type;

final class GenericType implements Type
{
    public function willHandle(Definition $definition): bool
    {
        return true;
    }

    public function handle(Definition $definition): Definition
    {
        return $definition->withMergedPayload([
            'template' => $definition->type(),
        ]);
    }
}
