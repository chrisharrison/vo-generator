<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator;

interface ValueObject
{
    public function isNull(): bool;
    public function isNotNull(): bool;
    public function isSame(ValueObject $compare): bool;
    public function isNotSame(ValueObject $compare): bool;
    public static function fromNative(mixed $native): static;
    public function toNative(): mixed;
    public static function null(): static;
    public function whenNull(mixed $native): static;
}
