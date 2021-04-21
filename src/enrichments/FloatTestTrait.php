<?php

namespace ChrisHarrison\VoGenerator\Test;

use ChrisHarrison\VoGenerator\Attributes\Enriches;
use ValueObjects\FloatTest;

#[Enriches('FloatTest')]
trait FloatTestTrait
{
    public function magicNumber()
    {
        return FloatTest::fromNative(5);
    }
}