<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\CodeStreamOut;

use ChrisHarrison\VoGenerator\Exceptions\EvaledCodeFailure;
use Throwable;

use function implode;

final class Runtime implements CodeStreamOut
{
    private $buffer;

    public function setup(string $namespace): void
    {
        $this->buffer = [
            sprintf('namespace %s;', $namespace),
        ];
    }

    public function out(string $code): void
    {
        $this->buffer[] = $code;
    }

    public function finish(): void
    {
        $code = implode('', $this->buffer);
        try {
            eval($code);
        } catch (Throwable $e) {
            throw new EvaledCodeFailure($code, $e);
        }
    }
}
