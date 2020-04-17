<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\Registry;

use Iterator;

interface Registry extends Iterator
{
    public function resolve(string $fullClassName): ?string;
}
