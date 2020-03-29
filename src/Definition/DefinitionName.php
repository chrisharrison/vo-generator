<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\Definition;

use Assert\Assert;

use function sprintf;

final class DefinitionName
{
    private $value;

    public function __construct(string $value)
    {
        Assert::that($value, function (array $params) {
            return sprintf('%s is not a valid PHP class name', $params['value']);
        })->nullOr()->regex('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/');
        $this->value = $value;
    }

    public function toString(): string
    {
        return $this->value;
    }
}
