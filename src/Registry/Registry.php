<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\Registry;

use Iterator;

interface Registry extends Iterator
{
    public function resolve(string $fullClassName): ?string;

    /**
     * @param callable $filterMethod
     * @return static
     */
    public function filter(callable $filterMethod);
}
