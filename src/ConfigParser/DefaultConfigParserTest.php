<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\ConfigParser;

use ChrisHarrison\VoGenerator\Config\Config;
use ChrisHarrison\VoGenerator\Pathfinder\Pathfinder;
use PHPUnit\Framework\TestCase;

final class DefaultConfigParserTest extends TestCase
{
    public function test_paths_are_replaced_in_config_values()
    {
        $config = new Config([
            'test1' => ['{packagePath}/test'],
            'test2' => '{rootPath}/test',
        ]);

        $pathfinder = $this->prophesize(Pathfinder::class);
        $pathfinder->rootPath()->willReturn('ROOT_PATH');
        $pathfinder->packagePath()->willReturn('PACKAGE_PATH');

        $parser = new DefaultConfigParser($pathfinder->reveal(), []);

        $parsedConfig = $parser->parse($config);

        $this->assertEquals([
            'test1' => ['PACKAGE_PATH/test'],
            'test2' => 'ROOT_PATH/test',
        ], $parsedConfig->all());
    }

    public function test_type_system_is_conformed_to()
    {
        $pathfinder = $this->prophesize(Pathfinder::class);
        $pathfinder->rootPath()->willReturn('ROOT_PATH');
        $pathfinder->packagePath()->willReturn('PACKAGE_PATH');

        $parser = new DefaultConfigParser(
            $pathfinder->reveal(),
            [
                'abc' => 'string',
                'def' => 'array',
            ]
        );

        $this->assertEquals(new Config([
            'abc' => 'two',
            'def' => ['three'],
        ]), $parser->parse(new Config([
            'abc' => ['one', 'two'],
            'def' => 'three',
        ])));
    }
}
