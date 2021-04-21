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
            return $acc->addOne($definition)->add($this->extendOne($definition));
        }, new Definitions());
    }

    private function extendOne(Definition $definition): Definitions
    {
        return array_reduce($this->extensions, function (Definitions $acc, Extension $extension) use ($definition) {
            $extendedDefinition = $extension->extend($definition);
            if ($extendedDefinition !== null) {
                $acc = $acc->addOne($extendedDefinition);
            }
            return $acc;
        }, new Definitions());
    }
}
