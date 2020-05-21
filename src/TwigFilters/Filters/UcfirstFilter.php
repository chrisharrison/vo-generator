<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\TwigFilters\Filters;

use ChrisHarrison\VoGenerator\TwigFilters\TwigFilterMaker;
use Twig\TwigFilter;

final class UcfirstFilter implements TwigFilterMaker
{
    public function make(): TwigFilter
    {
        return new TwigFilter('ucfirst', function (string $value) {
            return ucfirst($value);
        });
    }
}
