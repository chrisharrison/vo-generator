<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\Pathfinder;

interface Pathfinder
{
    public function rootPath(): string;
    public function packagePath(): string;
}
