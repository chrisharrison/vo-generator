<?php

namespace ChrisHarrison\VoGenerator\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class Enriches
{
    private $value;

    public function __construct($value = null)
    {
        $this->value = $value;
    }
}
