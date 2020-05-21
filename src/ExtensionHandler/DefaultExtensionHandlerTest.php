<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\ExtensionHandler;

use ChrisHarrison\VoGenerator\Definition\Definition;
use ChrisHarrison\VoGenerator\Definition\Definitions;
use ChrisHarrison\VoGenerator\Extension\Extension;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

final class DefaultExtensionHandlerTest extends TestCase
{
    public function test_it_extends_a_definition_by_calling_extensions()
    {
        $definitions = new Definitions([
            new Definition([
                'name' => 'base1',
            ]),
            new Definition([
                'name' => 'base2',
            ]),
        ]);

        $makeExtendFunction = function (string $suffix) {
            return function (array $args) use ($suffix) {
                /* @var Definition $definition */
                $definition = $args[0];
                return $definition->withMergedPayload(['name' => $definition->name()->toString() . $suffix]);
            };
        };

        $extension1 = $this->prophesize(Extension::class);
        $extension1->extend(Argument::any())->will($makeExtendFunction('1'));
        $extension2 = $this->prophesize(Extension::class);
        $extension2->extend(Argument::any())->will($makeExtendFunction('2'));

        $handler = new DefaultExtensionHandler([
            $extension1->reveal(),
            $extension2->reveal(),
        ]);

        $result = $handler->extend($definitions);

        $this->assertEquals([
            ['name' => 'base1'],
            ['name' => 'base11'],
            ['name' => 'base12'],
            ['name' => 'base2'],
            ['name' => 'base21'],
            ['name' => 'base22'],
        ], array_map(function (Definition $definition) {
            return $definition->payload();
        }, $result->toArray()));
    }
}
