<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator;

interface Entity extends HasInternalProperties
{
    /**
     * @return ValueObject
     */
    public function id();
}
