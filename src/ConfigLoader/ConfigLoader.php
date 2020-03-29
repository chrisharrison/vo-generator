<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\ConfigLoader;

interface ConfigLoader
{
    public function load(): array;
}
