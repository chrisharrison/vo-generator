<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\Renderer;

interface Renderer
{
    public function render(string $template, array $definition): string;
}
