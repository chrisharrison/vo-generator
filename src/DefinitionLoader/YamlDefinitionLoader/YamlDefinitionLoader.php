<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\DefinitionLoader\YamlDefinitionLoader;

use ChrisHarrison\VoGenerator\Definition\Definition;
use ChrisHarrison\VoGenerator\Definition\DefinitionName;
use ChrisHarrison\VoGenerator\Definition\Definitions;
use ChrisHarrison\VoGenerator\DefinitionLoader\DefinitionLoader;
use ChrisHarrison\VoGenerator\Exceptions\TypeDoesNotExist;
use ChrisHarrison\VoGenerator\TypeHandler\TypeHandler;
use PHP_CodeSniffer\Tokenizers\PHP;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use Symfony\Component\Yaml\Yaml;

use function array_diff_key;
use function array_map;

final class YamlDefinitionLoader implements DefinitionLoader
{
    private $typeHandler;
    private $rootPath;

    public function __construct(
        TypeHandler $typeHandler,
        string $rootPath
    ) {
        $this->typeHandler = $typeHandler;
        $this->rootPath = $rootPath;
    }

    public function load(): Definitions
    {
        $definitions = new Definitions();
        $fileIterator = new RecursiveDirectoryIterator($this->rootPath);
        foreach (new RecursiveIteratorIterator($fileIterator) as $filename) {
            /** @var SplFileInfo $filename */
            if (strpos(strrev($filename->getFilename()), strrev('.vo.yml')) !== 0) {
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
            $name = new DefinitionName($raw['name']);
            $type = $this->typeHandler->get($raw['type']);
            if ($type === null) {
                throw new TypeDoesNotExist($raw['type']);
            }
            $additionalProperties = array_diff_key($raw, ['name' => null, 'type' => 'null']);
            return new Definition(
                $name,
                $type,
                $additionalProperties
            );
        }, $input));
    }
}
