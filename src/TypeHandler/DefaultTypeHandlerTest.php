<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\TypeHandler;

use ChrisHarrison\VoGenerator\Definition\Definition;
use ChrisHarrison\VoGenerator\Type\Type;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\Container\ContainerInterface;

final class DefaultTypeHandlerTest extends TestCase
{
    public function test_it_builds_handlers_and_calls_them_if_they_declare_they_handle()
    {
        $definition = new Definition([
            'name' => 'StartingValue',
        ]);

        $type1Name = 'type1';
        $type2Name = 'type2';

        $type1 = $this->prophesize(Type::class);
        $type1->willHandle(Argument::any())->willReturn(false);
        $type1->handle(Argument::any())->willReturn($definition);

        $type2 = $this->prophesize(Type::class);
        $type2->willHandle(Argument::any())->willReturn(true);
        $type2->handle(Argument::any())->will(function (array $args) {
            return new Definition([
                'name' => $args[0]->name()->toString() . 'With2',
            ]);
        });

        $container = $this->prophesize(ContainerInterface::class);
        $container->get($type1Name)->willReturn($type1->reveal());
        $container->get($type2Name)->willReturn($type2->reveal());

        $handler = new DefaultTypeHandler($container->reveal(), [$type1Name, $type2Name]);
        $handledDefinition = $handler->handle($definition);

        $type1->willHandle(Argument::any())->shouldHaveBeenCalledOnce();
        $type2->willHandle(Argument::any())->shouldHaveBeenCalledOnce();

        $type1->handle(Argument::any())->shouldNotBeCalled();
        $type2->handle(Argument::any())->shouldHaveBeenCalledOnce();

        $this->assertEquals('StartingValueWith2', $handledDefinition->name()->toString());
    }
}
