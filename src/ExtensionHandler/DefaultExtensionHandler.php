<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\ExtensionHandler;

use ChrisHarrison\VoGenerator\Definition\Definition;
use ChrisHarrison\VoGenerator\Definition\Definitions;
use ChrisHarrison\VoGenerator\Extension\Extension;

final class DefaultExtensionHandler implements ExtensionHandler
{
    private array $extensions;

    public function __construct(array $extensions)
    {
        $this->extensions = $extensions;
    }

    public function extend(Definitions $definitions): Definitions
    {
        return array_reduce($definitions->toArray(), function (Definitions $acc, Definition $definition) {
            return $acc->addOne($this->extendOne($definition));
        }, new Definitions());
    }

    private function extendOne(Definition $definition): Definition
    {
        return array_reduce($this->extensions, function (Definition $definition, Extension $extension) {
            return $extension->extend($definition);
        }, $definition);
    }
}
