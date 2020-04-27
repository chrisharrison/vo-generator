<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator;

trait HookComposition
{
    public function composeHookable(callable $inner, string $functionName, ...$args)
    {
        $hookingMethodName = 'hook' . ucfirst(str_replace('_', '', $functionName));
        if (method_exists($this, $hookingMethodName)) {
            return $this->{$hookingMethodName}($inner, ...$args);
        } else {
            return $inner(...$args);
        }
    }
}
