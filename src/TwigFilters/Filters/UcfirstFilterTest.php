<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\TwigFilters\Filters;

use PHPUnit\Framework\TestCase;
use Twig\TwigFilter;

final class UcfirstFilterTest extends TestCase
{
    public function test_it_outputs_formatted_string_for_string()
    {
        $filter = (new UcfirstFilter())->make();
        $this->assertInstanceOf(TwigFilter::class, $filter);
        $this->assertEquals('ucfirst', $filter->getName());

        $callable = $filter->getCallable();
        $formatted1 = $callable('UPPERCASE');
        $formatted2 = $callable('lowercase');
        $formatted3 = $callable('camelCase');

        $this->assertEquals('UPPERCASE', $formatted1);
        $this->assertEquals('Lowercase', $formatted2);
        $this->assertEquals('CamelCase', $formatted3);
    }
}
