<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\TwigFilters\Filters;

use PHPUnit\Framework\TestCase;
use Twig\TwigFilter;

final class ImplementsFilterTest extends TestCase
{
    public function test_it_outputs_formatted_string_for_array()
    {
        $filter = (new ImplementsFilter())->make();
        $this->assertInstanceOf(TwigFilter::class, $filter);
        $this->assertEquals('implements', $filter->getName());

        $callable = $filter->getCallable();
        $formatted = $callable(['Vendor\\Package\\ClassOne', 'Vendor\\Package\\ClassTwo']);

        $this->assertEquals(
            '\\Vendor\\Package\\ClassOne, \\Vendor\\Package\\ClassTwo',
            $formatted
        );
    }
}
