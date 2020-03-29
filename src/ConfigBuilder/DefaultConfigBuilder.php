<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\ConfigBuilder;

use ChrisHarrison\VoGenerator\ConfigLoader\ConfigLoader;

final class DefaultConfigBuilder implements ConfigBuilder
{
    private $configLoader;
    private $rootPath;
    private $packageRootPath;
    private $injectedConfig;

    public function __construct(
        ConfigLoader $configLoader,
        string $rootPath,
        string $packageRootPath,
        array $injectedConfig
    ) {
        $this->configLoader = $configLoader;
        $this->rootPath = $rootPath;
        $this->packageRootPath = $packageRootPath;
        $this->injectedConfig = $injectedConfig;
    }

    public function build(): array
    {
        $default = [
            'namespace' => 'ValueObjects',
            'templateDirs' => [
                $this->packageRootPath . '/templates'
            ],
            'definitionFileRoot' => $this->rootPath,
        ];
        return array_merge_recursive(
            $default,
            $this->configLoader->load(),
            $this->injectedConfig
        );
    }
}
