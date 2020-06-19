<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\ConfigParser;

use ChrisHarrison\VoGenerator\Config\Config;
use ChrisHarrison\VoGenerator\Pathfinder\Pathfinder;
use Noodlehaus\ConfigInterface;

final class DefaultConfigParser implements ConfigParser
{
    private $pathfinder;
    private $types;

    public function __construct(
        Pathfinder $pathfinder,
        array $types
    ) {
        $this->pathfinder = $pathfinder;
        $this->types = $types;
    }

    public function parse(ConfigInterface $config): ConfigInterface
    {
        $configItems = $config->all();
        $configItems = $this->conformTypes($configItems);
        return new Config($this->parseValue($configItems));
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

    private function conformTypes(array $config): array
    {
        foreach ($config as $key => $value) {
            $formatter = function ($type, $value) {
                if ($type === 'string') {
                    if (is_array($value)) {
                        return (string) $value[array_key_last($value)];
                    }
                    return (string) $value;
                }
                if ($type === 'array') {
                    if (is_array($value)) {
                        return $value;
                    }
                    return [$value];
                }
                return $value;
            };
            $config[$key] = $formatter($this->types[$key] ?? null, $value);
        }
        return $config;
    }
}
