<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\CodeStreamOut;

final class Runtime implements CodeStreamOut
{
    private $namespace;

    public function setup(string $namespace): void
    {
        $this->namespace = $namespace;
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
        $tmpPath = sys_get_temp_dir() . '/' . $filename . '.php';
        file_put_contents(
            $tmpPath,
            implode(PHP_EOL, $out)
        );
        require($tmpPath);
    }

    public function finish(): void
    {
    }
}
