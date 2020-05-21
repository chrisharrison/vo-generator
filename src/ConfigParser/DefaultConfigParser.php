<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\ConfigParser;

use ChrisHarrison\VoGenerator\Config\Config;
use ChrisHarrison\VoGenerator\Pathfinder\Pathfinder;
use Noodlehaus\ConfigInterface;

final class DefaultConfigParser implements ConfigParser
{
    private $pathfinder;

    public function __construct(
        Pathfinder $pathfinder
    ) {
        $this->pathfinder = $pathfinder;
    }

    public function parse(ConfigInterface $config): ConfigInterface
    {
        return new Config($this->parseValue($config->all()));
    }

    /**
     * @param string|array $value
     * @return string|array
     */
    private function parseValue($value)
    {
        if (is_array($value)) {
            return array_map(function ($innerValue) {
                return $this->parseValue($innerValue);
            }, $value);
        }

        return str_replace([
            '{rootPath}',
            '{packagePath}',
        ], [
            $this->pathfinder->rootPath(),
            $this->pathfinder->packagePath(),
        ], $value);
    }
}
