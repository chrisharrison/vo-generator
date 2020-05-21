<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\DefinitionLoader\YamlDefinitionLoader;

use ChrisHarrison\VoGenerator\Definition\Definition;
use ChrisHarrison\VoGenerator\Definition\Definitions;
use ChrisHarrison\VoGenerator\DefinitionLoader\DefinitionLoader;
use ChrisHarrison\VoGenerator\TypeHandler\TypeHandler;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use Symfony\Component\Yaml\Yaml;

final class YamlDefinitionLoader implements DefinitionLoader
{
    private $typeHandler;
    private $rootPath;
    private $fileExtension;

    public function __construct(
        TypeHandler $typeHandler,
        string $rootPath,
        string $fileExtension
    ) {
        $this->typeHandler = $typeHandler;
        $this->rootPath = $rootPath;
        $this->fileExtension = $fileExtension;
    }

    public function load(): Definitions
    {
        $definitions = new Definitions();
        $fileIterator = new RecursiveDirectoryIterator($this->rootPath);
        foreach (new RecursiveIteratorIterator($fileIterator) as $filename) {
            /** @var SplFileInfo $filename */
            if (strpos(strrev($filename->getFilename()), strrev('.' . $this->fileExtension)) !== 0) {
                continue;
            }
            $definitions = $definitions->add($this->loadFile((string) $filename));
        }
        return $definitions;
    }

    private function loadFile(string $path): Definitions
    {
        $input = Yaml::parseFile($path);

        return new Definitions(array_map(function (array $raw) {
            return new Definition($raw);
        }, $input));
    }
}
