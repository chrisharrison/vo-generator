<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\DefinitionLoader\VomlDefinitionLoader;

use ChrisHarrison\VoGenerator\Definition\Definitions;
use ChrisHarrison\VoGenerator\DefinitionLoader\DefinitionLoader;
use ChrisHarrison\VoGenerator\DefinitionLoader\VomlDefinitionLoader\Parser\VomlParser;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

final class VomlDefinitionLoader implements DefinitionLoader
{
    private VomlParser $parser;
    private string $rootPath;
    private string $fileExtension;

    public function __construct(
        VomlParser $parser,
        string $rootPath,
        string $fileExtension
    ) {
        $this->parser = $parser;
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
        return $this->parser->parse(file_get_contents($path));
    }
}
