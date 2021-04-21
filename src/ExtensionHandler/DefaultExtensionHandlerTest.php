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

        $extendFunction = function (array $args) {
            /* @var Definition $definition */
            $definition = $args[0];
            return $definition->withMergedPayload(['name' => $definition->name()->toString() . '_extended']);
        };

        $extension1 = $this->prophesize(Extension::class);
        $extension1->extend(Argument::any())->will($extendFunction);

        $handler = new DefaultExtensionHandler([
            $extension1->reveal(),
        ]);

        $result = $handler->extend($definitions);

        $this->assertEquals([
            ['name' => 'base1_extended'],
            ['name' => 'base2_extended'],
        ], array_map(function (Definition $definition) {
            return $definition->payload();
        }, $result->toArray()));
    }
}
