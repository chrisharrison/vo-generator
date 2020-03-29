<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\ExtensionHandler;

use ChrisHarrison\VoGenerator\Definition\Definitions;

interface ExtensionHandler
{
    public function extend(Definitions $definitions): Definitions;
}
