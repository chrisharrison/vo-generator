<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\InternalEvaluator;

use ArrayIterator;
use ChrisHarrison\VoGenerator\CodeStreamer\CodeStreamer;
use ChrisHarrison\VoGenerator\CodeStreamOut\Runtime;
use ChrisHarrison\VoGenerator\Definition\DefinitionName;
use ChrisHarrison\VoGenerator\Registry\Registry;

final class DefaultInternalEvaluator implements InternalEvaluator
{
    private $codeStreamer;
    private $registry;
    private $namespace;

    public function __construct(
        CodeStreamer $codeStreamer,
        Registry $registry,
        string $namespace
    ) {
        $this->codeStreamer = $codeStreamer;
        $this->registry = $registry;
        $this->namespace = $namespace;
    }

    public function evaluate(DefinitionName $name): string
    {
        $className = $this->namespace . '\\' . $name->toString();
        if (class_exists($className)) {
            return $className;
        }
        $code = $this->registry->resolve($className);
        if ($code !== null) {
            $this->codeStreamer->stream(new ArrayIterator([$code]), new Runtime());
        }
        return $className;
    }
}
