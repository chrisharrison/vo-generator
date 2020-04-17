<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\Extension;

use ChrisHarrison\VoGenerator\Definition\Definition;

abstract class TypeExtension implements Extension
{
    public function extend(Definition $definition): ?Definition
    {
        return $definition->withMergedPayload([
            'name' => sprintf(
                '%s%s',
                $this->name(),
                $definition->name()->toString()
            ),
            'type' => strtolower($this->name()),
            'extendedFrom' => $definition->name()->toString(),
        ]);
    }

    abstract protected function name(): string;
}
