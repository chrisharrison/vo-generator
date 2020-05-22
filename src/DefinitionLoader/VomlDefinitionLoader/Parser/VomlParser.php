<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\DefinitionLoader\VomlDefinitionLoader\Parser;

use ChrisHarrison\VoGenerator\Definition\Definitions;

interface VomlParser
{
    public function parse(string $input): Definitions;
}
