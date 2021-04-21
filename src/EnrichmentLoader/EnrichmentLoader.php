<?php

namespace ChrisHarrison\VoGenerator\EnrichmentLoader;

use ChrisHarrison\VoGenerator\Attributes\Enriches;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use SplFileInfo;

final class EnrichmentLoader
{
    private array $paths;

    public function __construct(array $paths)
    {
        $this->paths = $paths;
    }

    public function load(): array
    {
        foreach ($this->paths as $path) {
            if (!file_exists($path)) {
                continue;
            }
            $fileIterator = new RecursiveDirectoryIterator($path);
            foreach (new RecursiveIteratorIterator($fileIterator) as $filename) {
                /** @var SplFileInfo $filename */
                if (strpos(strrev($filename->getFilename()), strrev('.' . 'php')) !== 0) {
                    continue;
                }
                require_once($filename);
            }
        }

        $enrichments = [];
        foreach (get_declared_traits() as $trait) {
            $reflection = new ReflectionClass($trait);
            if (count($reflection->getAttributes(Enriches::class)) > 0) {
                $enrichments[] = $reflection;
            }
        }
        return $enrichments;
    }
}