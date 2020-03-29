<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\App;

use Psr\Container\ContainerInterface;

interface App
{
    public function make(): ContainerInterface;
}
