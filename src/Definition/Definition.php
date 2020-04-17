<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\Definition;

final class Definition
{
    private $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    public function payload(): array
    {
        return $this->payload;
    }

    public function name(): DefinitionName
    {
        return new DefinitionName($this->payload['name']);
    }

    public function type(): string
    {
        return $this->payload['type'];
    }

    public function template(): string
    {
        return $this->payload['template'];
    }
    public function traits(): array
    {
        return $this->payload['traits'] ?? [];
    }

    public function withMergedPayload(array $payload): Definition
    {
        return new Definition(array_merge($this->payload, $payload));
    }
}
