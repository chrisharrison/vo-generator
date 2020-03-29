<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\ConfigLoader;

use Symfony\Component\Yaml\Yaml;

final class YamlConfigLoader implements ConfigLoader
{
    private $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function load(): array
    {
        if (file_exists($this->path)) {
            return Yaml::parseFile($this->path);
        }
        return [];
    }
}
