<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\TypeHandler;

use ChrisHarrison\VoGenerator\Definition\Definition;
use ChrisHarrison\VoGenerator\Exceptions\TypeDoesNotExist;
use ChrisHarrison\VoGenerator\Type\Type;

final class DefaultTypeHandler implements TypeHandler
{
    /** @var Type[] */
    private $types;

    public function __construct(
        array $types
    ) {
        $this->types = $types;
    }

    public function handle(Definition $definition): array
    {
        $default = [
            'name' => $definition->name()->toString(),
        ];
        foreach ($this->types as $type) {
            if ($type->name() === $definition->type()->name()) {
                return array_merge($default, $type->handle($definition));
            }
        }
        throw new TypeDoesNotExist($definition->type()->name());
    }

    public function get(string $name): ?Type
    {
        foreach ($this->types as $type) {
            if ($type->name() === $name) {
                return $type;
            }
        }
        return null;
    }
}
