<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\CodeStreamer;

use ChrisHarrison\VoGenerator\CodeStreamOut\CodeStreamOut;
use Iterator;

interface CodeStreamer
{
    public function stream(Iterator $input, CodeStreamOut $output): void;
}
