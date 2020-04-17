<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\Type;

use ChrisHarrison\VoGenerator\Definition\Definition;
use ReflectionClass;

abstract class SimpleType implements Type
{
    abstract public function handle(Definition $definition): Definition;

    public function willHandle(Definition $definition): bool
    {
        return $definition->type() === $this->name();
    }

    private function name(): string
    {
        return strtolower(str_replace('Type', '', (new ReflectionClass($this))->getShortName()));
    }
}
