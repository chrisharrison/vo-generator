<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator;

interface HasInternalProperties
{
    public static function properties(): array;
}
