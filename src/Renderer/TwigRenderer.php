<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\Renderer;

use Twig\Environment as TwigEnvironment;

final class TwigRenderer implements Renderer
{
    private $twig;

    public function __construct(TwigEnvironment $twig)
    {
        $this->twig = $twig;
    }

    public function render(string $template, array $definition): string
    {
        return $this->twig->render($template . '.twig', $definition);
    }
}
