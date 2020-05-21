<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\Renderer;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Twig\Environment as TwigEnvironment;

final class TwigRendererTest extends TestCase
{
    public function test_it_calls_the_twig_renderer_with_template_name_and_definition()
    {
        $templateName = 'template';
        $definition = ['one' => 'value', 'two' => 'value'];

        $twig = $this->prophesize(TwigEnvironment::class);
        $twig->render(Argument::cetera())->willReturn('render');

        $renderer = new TwigRenderer($twig->reveal());
        $render = $renderer->render($templateName, $definition);

        $twig->render($templateName . '.twig', $definition)->shouldHaveBeenCalledOnce();
        $this->assertEquals('render', $render);
    }
}
