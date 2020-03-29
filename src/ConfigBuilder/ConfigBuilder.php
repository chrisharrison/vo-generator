<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\ConfigBuilder;

interface ConfigBuilder
{
    public function build(): array;
}
