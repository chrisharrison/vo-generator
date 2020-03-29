<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\CodeStreamOut;

interface CodeStreamOut
{
    public function setup(string $namespace): void;
    public function out(string $code): void;
    public function finish(): void;
}
