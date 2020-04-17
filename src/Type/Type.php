<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\Type;

use ChrisHarrison\VoGenerator\Definition\Definition;

interface Type
{
    public function willHandle(Definition $definition): bool;
    public function handle(Definition $definition): Definition;
}
