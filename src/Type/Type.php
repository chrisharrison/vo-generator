<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\Type;

use ChrisHarrison\VoGenerator\Definition\Definition;

interface Type
{
    public function name(): string;
    public function handle(Definition $definition): array;
    public function template(): string;

    /**
     * @param string $template
     * @return static
     */
    public function withTemplate(string $template);
}
