<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator;

interface Entity extends HasInternalProperties
{
    public function id(): ValueObject;
}
