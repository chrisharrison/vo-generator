<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\Registry;

use ChrisHarrison\VoGenerator\Definition\DefinitionName;
use ChrisHarrison\VoGenerator\Definition\Definitions;
use ChrisHarrison\VoGenerator\ExtensionHandler\ExtensionHandler;
use ChrisHarrison\VoGenerator\Renderer\Renderer;
use ChrisHarrison\VoGenerator\TypeHandler\TypeHandler;

use function array_key_exists;
use function pathinfo;
use function str_replace;

use const PATHINFO_BASENAME;

final class DefaultRegistry implements Registry
{
    private $definitions;
    private $extensionHandler;
    private $typeHandler;
    private $renderer;

    private $position;

    public function __construct(
        Definitions $definitions,
        ExtensionHandler $extensionHandler,
        TypeHandler $typeHandler,
        Renderer $renderer
    ) {
        $this->definitions = $extensionHandler->extend($definitions);
        $this->extensionHandler = $extensionHandler;
        $this->typeHandler = $typeHandler;
        $this->renderer = $renderer;

        $this->position = 0;
    }

    public function resolve(string $name): ?string
    {
        $definitionName = $this->getDefinitionNameFromFQN($name);
        $definition = $this->definitions->get($definitionName);
        if ($definition === null) {
            return null;
        }

        return $this->renderer->render(
            $definition->type()->template(),
            $this->typeHandler->handle($definition)
        );
    }

    private function getDefinitionNameFromFQN(string $fqn): DefinitionName
    {
        return new DefinitionName(
            pathinfo(str_replace('\\', '/', $fqn), PATHINFO_BASENAME)
        );
    }

    public function current()
    {
        return $this->resolve(
            $this->definitions->toArray()[$this->position]->name()->toString()
        );
    }

    public function next()
    {
        ++$this->position;
    }

    public function key()
    {
        return $this->position;
    }

    public function valid()
    {
        return (array_key_exists($this->position, $this->definitions->toArray()));
    }

    public function rewind()
    {
        $this->position = 0;
    }
}
