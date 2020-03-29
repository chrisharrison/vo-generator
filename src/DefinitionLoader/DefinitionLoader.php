<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\DefinitionLoader;

use ChrisHarrison\VoGenerator\Definition\Definitions;

interface DefinitionLoader
{
    public function load(): Definitions;
}
