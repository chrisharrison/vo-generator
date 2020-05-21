<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\TwigFilters\Filters;

use ChrisHarrison\VoGenerator\TwigFilters\TwigFilterMaker;
use Twig\TwigFilter;

final class ImplementsFilter implements TwigFilterMaker
{
    public function make(): TwigFilter
    {
        return new TwigFilter('implements', function (array $implements) {
            return implode(', ', array_map(function (string $implement) {
                return '\\' . $implement;
            }, $implements));
        });
    }
}
