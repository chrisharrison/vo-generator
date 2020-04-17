<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\InternalEvaluator;

use ChrisHarrison\VoGenerator\Definition\DefinitionName;

interface InternalEvaluator
{
    public function evaluate(DefinitionName $name): string;
}
