<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\DefinitionLoader\VomlDefinitionLoader\Parser;

use ChrisHarrison\VoGenerator\Definition\Definitions;

final class DefaultVomlParser implements VomlParser
{
    private Parser $parser;

    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    public function parse(string $input): Definitions
    {
        return $this->parser->parse($input);
    }
}
