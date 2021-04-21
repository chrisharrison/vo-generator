<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\Extension;

use ChrisHarrison\VoGenerator\Definition\Definition;
use ReflectionClass;

final class EnrichmentExtension implements Extension
{
    /**
     * @var ReflectionClass[]
     */
    private array $enrichments;

    public function __construct(array $enrichments)
    {
        $this->enrichments = $enrichments;
    }

    public function extend(Definition $definition): Definition
    {
        foreach ($this->enrichments as $enrichment) {
            $attributes = $enrichment->getAttributes();
            foreach ($attributes as $attribute) {
                if (in_array($definition->name()->toString(), $attribute->getArguments())) {
                    $definition = $definition->withMergedPayload([
                        'traits' => array_merge($definition->traits(), [$enrichment->name]),
                    ]);
                }
            }
        }
        return $definition;
    }
}
