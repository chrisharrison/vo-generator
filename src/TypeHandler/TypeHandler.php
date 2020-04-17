<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\TypeHandler;

use ChrisHarrison\VoGenerator\Definition\Definition;

interface TypeHandler
{
    public function handle(Definition $definition): Definition;
}
