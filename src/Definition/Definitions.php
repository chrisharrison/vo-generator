<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\Definition;

use InvalidArgumentException;

final class Definitions
{
    private array $definitions;

    private $mapping;

    public function __construct(array $definitions = [])
    {
        $this->definitions = $definitions;
        $this->mapping = array_reduce($definitions, function (array $acc, Definition $definition) {
            if (array_key_exists($definition->name()->toString(), $acc)) {
                throw new InvalidArgumentException(sprintf(
                    'Tried to define a value object: %s more than once',
                    $definition->name()->toString()
                ));
            }
            $acc[$definition->name()->toString()] = count($acc);
            return $acc;
        }, []);
    }

    public function get(DefinitionName $name): ?Definition
    {
        if (array_key_exists($name->toString(), $this->mapping)) {
            return $this->definitions[$this->mapping[$name->toString()]];
        }
        return null;
    }

    public function addOne(Definition $definition): Definitions
    {
        $clone = clone $this;
        return $clone->add(new Definitions([$definition]));
    }

    public function add(Definitions $definitions): Definitions
    {
        return new Definitions(array_merge($this->definitions, $definitions->definitions));
    }

    /**
     * @return Definition[]
     */
    public function toArray(): array
    {
        return $this->definitions;
    }
}
