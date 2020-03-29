<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\CodeStreamOut;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

use function file_put_contents;
use function mkdir;
use function strlen;
use function strpos;
use function unlink;

use const PHP_EOL;

final class MultiFile implements CodeStreamOut
{
    private $path;
    private $namespace;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function setup(string $namespace): void
    {
        $this->namespace = $namespace;
        $this->rmDir($this->path);
        mkdir($this->path);
    }

    public function out(string $code): void
    {
        $out = [
            '<?php',
            '',
            'declare(strict_types=1);',
            '',
            sprintf('namespace %s;', $this->namespace),
            '',
            $code,
        ];
        $first = strpos($code, 'class ') + strlen('class ');
        $last = strpos($code, ' ', $first);
        $filename = substr($code, $first, $last - $first);
        file_put_contents(
            $this->path . '/' . $filename . '.php',
            implode(PHP_EOL, $out)
        );
    }

    public function finish(): void
    {
        // not needed
    }

    private function rmDir(string $path): void
    {
        if (!file_exists($path)) {
            return;
        }
        $iterator = new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $file) {
            /** @var SplFileInfo $file */
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        rmdir($path);
    }
}
