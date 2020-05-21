<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\ConfigParser;

use Noodlehaus\ConfigInterface;

interface ConfigParser
{
    public function parse(ConfigInterface $config): ConfigInterface;
}
