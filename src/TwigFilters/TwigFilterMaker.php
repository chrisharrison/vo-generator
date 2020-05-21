<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\TwigFilters;

use Twig\TwigFilter;

interface TwigFilterMaker
{
    public function make(): TwigFilter;
}
