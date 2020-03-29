<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\CodeStreamer;

use ChrisHarrison\VoGenerator\CodeStreamOut\CodeStreamOut;
use Iterator;

final class DefaultCodeStreamer implements CodeStreamer
{
    private $namespace;

    public function __construct(string $namespace)
    {
        $this->namespace = $namespace;
    }

    public function stream(Iterator $in, CodeStreamOut $out): void
    {
        $out->setup($this->namespace);
        foreach ($in as $tick) {
            $out->out($tick);
        }
        $out->finish();
    }
}
