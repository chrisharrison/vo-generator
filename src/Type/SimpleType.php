<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\Type;

use ChrisHarrison\VoGenerator\Definition\Definition;

abstract class SimpleType implements Type
{
    private $template;

    abstract public function name(): string;

    abstract public function enrichProperties(Definition $definition): array;

    public function handle(Definition $definition): array
    {
        return array_merge(
            $definition->additionalProperties(),
            $this->enrichProperties($definition)
        );
    }

    public function template(): string
    {
        return $this->template !== null ? $this->template : strtolower($this->name());
    }

    /**
     * @param string $template
     * @return static
     */
    public function withTemplate(string $template)
    {
        $type = clone $this;
        $type->template = $template;
        return $type;
    }
}
