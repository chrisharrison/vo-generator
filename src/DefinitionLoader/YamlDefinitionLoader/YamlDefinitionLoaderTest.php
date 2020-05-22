<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\DefinitionLoader\YamlDefinitionLoader;

use ChrisHarrison\VoGenerator\Definition\Definition;
use ChrisHarrison\VoGenerator\Definition\Definitions;
use PHPUnit\Framework\TestCase;

final class YamlDefinitionLoaderTest extends TestCase
{
    public function test_it_loads_definitions_from_yml()
    {
        $loader = new YamlDefinitionLoader(
            __DIR__,
            'yml'
        );

        $load = $loader->load();

        $expected = new Definitions([
            new Definition([
                'name' => 'TestName',
                'type' => 'testtype',
                'arbitraryProperty' => 'test',
            ]),
            new Definition([
                'name' => 'TestName2',
                'type' => 'anothertype',
            ]),
        ]);

        $this->assertEquals($expected->toArray(), $load->toArray());
    }
}
