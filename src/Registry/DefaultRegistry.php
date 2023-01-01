<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\Registry;

use ChrisHarrison\VoGenerator\Definition\DefinitionName;
use ChrisHarrison\VoGenerator\Definition\Definitions;
use ChrisHarrison\VoGenerator\ExtensionHandler\ExtensionHandler;
use ChrisHarrison\VoGenerator\Renderer\Renderer;
use ChrisHarrison\VoGenerator\TypeHandler\TypeHandler;

final class DefaultRegistry implements Registry
{
    private Definitions $definitions;
    private ExtensionHandler $extensionHandler;
    private TypeHandler $typeHandler;
    private Renderer $renderer;
    private string $namespace;

    private int $position;

    public function __construct(
        Definitions $definitions,
        ExtensionHandler $extensionHandler,
        TypeHandler $typeHandler,
        Renderer $renderer,
        string $namespace
    ) {
        $this->definitions = $extensionHandler->extend($definitions);
        $this->extensionHandler = $extensionHandler;
        $this->typeHandler = $typeHandler;
        $this->renderer = $renderer;
        $this->namespace = $namespace;

        $this->position = 0;
    }

    public function resolve(string $fullClassName): ?string
    {
        $parts = explode('\\', $fullClassName);
        if (count($parts) !== 2) {
            return null;
        }
        if ($parts[0] !== $this->namespace) {
            return null;
        }
        $definitionName = new DefinitionName($parts[1]);
        $definition = $this->definitions->get($definitionName);
        if ($definition === null) {
            return null;
        }

        $typedDefinition = $this->typeHandler->handle($definition);

        return $this->renderer->render(
            $typedDefinition->template(),
            $typedDefinition->payload()
        );
    }

    public function current(): ?string
    {
        return $this->resolve(
            $this->namespace . '\\' . $this->definitions->toArray()[$this->position]->name()->toString()
        );
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return (array_key_exists($this->position, $this->definitions->toArray()));
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function filter(callable $filterMethod): static
    {
        $clone = clone $this;
        $clone->definitions = new Definitions(
            array_filter($clone->definitions->toArray(), $filterMethod)
        );
        return $clone;
    }
}
