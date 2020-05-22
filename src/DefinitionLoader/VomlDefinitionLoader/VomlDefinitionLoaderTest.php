<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\DefinitionLoader\VomlDefinitionLoader;

use ChrisHarrison\VoGenerator\Definition\Definitions;
use ChrisHarrison\VoGenerator\DefinitionLoader\VomlDefinitionLoader\Parser\VomlParser;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

final class VomlDefinitionLoaderTest extends TestCase
{
    public function test_it_loads_definitions_from_vo_Schema()
    {
        $parser = $this->prophesize(VomlParser::class);
        $parser->parse(Argument::any())->willReturn(new Definitions([]));

        $loader = new VomlDefinitionLoader(
            $parser->reveal(),
            __DIR__,
            'voml-test'
        );

        $loader->load();

        $parser->parse('TEST')->shouldHaveBeenCalledOnce();
    }
}
