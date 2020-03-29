<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\TypeHandler;

use ChrisHarrison\VoGenerator\Definition\Definition;
use ChrisHarrison\VoGenerator\Type\Type;

interface TypeHandler
{
    public function handle(Definition $definition): array;
    public function get(string $name): ?Type;
}
