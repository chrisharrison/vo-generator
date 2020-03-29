<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\Definition;

use ChrisHarrison\VoGenerator\Type\Type;

final class Definition
{
    private $name;
    private $type;
    private $additionalProperties;

    public function __construct(
        DefinitionName $name,
        Type $type,
        array $additionalProperties
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->additionalProperties = $additionalProperties;
    }

    public function name(): DefinitionName
    {
        return $this->name;
    }

    public function type(): Type
    {
        return $this->type;
    }

    public function additionalProperties(): array
    {
        return $this->additionalProperties;
    }
}
