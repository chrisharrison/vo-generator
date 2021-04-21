<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\Extension;

use ChrisHarrison\VoGenerator\Definition\Definition;

interface Extension
{
    public function extend(Definition $definition): Definition;
}
