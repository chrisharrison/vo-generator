<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\Extension;

use ChrisHarrison\VoGenerator\Definition\Definition;
use ChrisHarrison\VoGenerator\Definition\DefinitionName;

use function array_merge;
use function sprintf;

abstract class TemplateExtension implements Extension
{
    public function extend(Definition $definition): ?Definition
    {
        return new Definition(
            new DefinitionName(sprintf(
                '%s%s',
                $this->name(),
                $definition->name()->toString()
            )),
            $definition->type()->withTemplate(strtolower($this->name())),
            array_merge(
                ['extendedFrom' => $definition->name()->toString()],
                $definition->additionalProperties()
            )
        );
    }

    abstract protected function name(): string;
}
