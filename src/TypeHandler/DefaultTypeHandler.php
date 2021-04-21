<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\TypeHandler;

use ChrisHarrison\VoGenerator\Definition\Definition;
use ChrisHarrison\VoGenerator\Type\Type;
use Psr\Container\ContainerInterface;

final class DefaultTypeHandler implements TypeHandler
{
    private ContainerInterface $container;

    /** @var string[] */
    private array $types;

    /** @var ?Type[] */
    private ?array $builtTypes = null;

    public function __construct(
        ContainerInterface $container,
        array $types
    ) {
        $this->container = $container;
        $this->types = $types;
    }

    public function handle(Definition $definition): Definition
    {
        foreach ($this->builtTypes() as $type) {
            if ($type->willHandle($definition)) {
                return $type->handle($definition);
            }
        }
        return $definition;
    }

    /**
     * @return Type[]
     */
    private function builtTypes(): array
    {
        if ($this->builtTypes !== null) {
            return $this->builtTypes;
        }
        return $this->builtTypes = array_map(function (string $typeHandlerName) {
            return $this->container->get($typeHandlerName);
        }, $this->types);
    }
}
