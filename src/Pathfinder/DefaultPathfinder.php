<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\Pathfinder;

final class DefaultPathfinder implements Pathfinder
{
    public function rootPath(): string
    {
        $rootLocations = [
            __DIR__ . '/../../../../../vendor/autoload.php',
            __DIR__ . '/../../vendor/autoload.php',
        ];
        foreach ($rootLocations as $rootLocation) {
            if (file_exists($rootLocation)) {
                return realpath(dirname($rootLocation) . '/../');
            }
        }
    }

    public function packagePath(): string
    {
        $installedPath = $this->rootPath() . '/vendor/chrisharrison/vo-generator';
        if (file_exists($installedPath)) {
            return $installedPath;
        }
        return $this->rootPath();
    }
}
