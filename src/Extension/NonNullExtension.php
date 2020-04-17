<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\Extension;

final class NonNullExtension extends TypeExtension implements Extension
{
    protected function name(): string
    {
        return 'NonNull';
    }
}
