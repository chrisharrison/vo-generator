<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\App;

use ChrisHarrison\VoGenerator\CodeStreamer\CodeStreamer;
use ChrisHarrison\VoGenerator\CodeStreamer\DefaultCodeStreamer;
use ChrisHarrison\VoGenerator\Config\Config;
use ChrisHarrison\VoGenerator\Config\DefaultConfig;
use ChrisHarrison\VoGenerator\ConfigParser\ConfigParser;
use ChrisHarrison\VoGenerator\ConfigParser\DefaultConfigParser;
use ChrisHarrison\VoGenerator\DefinitionLoader\DefinitionLoader;
use ChrisHarrison\VoGenerator\DefinitionLoader\VomlDefinitionLoader\Lexer\Lexer;
use ChrisHarrison\VoGenerator\DefinitionLoader\VomlDefinitionLoader\Parser\DefaultVomlParser;
use ChrisHarrison\VoGenerator\DefinitionLoader\VomlDefinitionLoader\Parser\Parser;
use ChrisHarrison\VoGenerator\DefinitionLoader\VomlDefinitionLoader\Parser\VomlParser;
use ChrisHarrison\VoGenerator\DefinitionLoader\VomlDefinitionLoader\VomlDefinitionLoader;
use ChrisHarrison\VoGenerator\DefinitionLoader\YamlDefinitionLoader\YamlDefinitionLoader;
use ChrisHarrison\VoGenerator\ExtensionHandler\DefaultExtensionHandler;
use ChrisHarrison\VoGenerator\ExtensionHandler\ExtensionHandler;
use ChrisHarrison\VoGenerator\InternalEvaluator\DefaultInternalEvaluator;
use ChrisHarrison\VoGenerator\InternalEvaluator\InternalEvaluator;
use ChrisHarrison\VoGenerator\Pathfinder\DefaultPathfinder;
use ChrisHarrison\VoGenerator\Pathfinder\Pathfinder;
use ChrisHarrison\VoGenerator\Renderer\Renderer;
use ChrisHarrison\VoGenerator\Renderer\TwigRenderer;
use ChrisHarrison\VoGenerator\Registry\DefaultRegistry;
use ChrisHarrison\VoGenerator\Registry\Registry;
use ChrisHarrison\VoGenerator\TwigFilters\Filters\ImplementsFilter;
use ChrisHarrison\VoGenerator\TwigFilters\Filters\UcfirstFilter;
use ChrisHarrison\VoGenerator\Type\DefaultTypes\CompositeType;
use ChrisHarrison\VoGenerator\Type\DefaultTypes\EntitySetType;
use ChrisHarrison\VoGenerator\Type\DefaultTypes\EntityType;
use ChrisHarrison\VoGenerator\Type\DefaultTypes\EnumType;
use ChrisHarrison\VoGenerator\Type\DefaultTypes\GenericType;
use ChrisHarrison\VoGenerator\Type\DefaultTypes\SetType;
use ChrisHarrison\VoGenerator\TypeHandler\DefaultTypeHandler;
use ChrisHarrison\VoGenerator\TypeHandler\TypeHandler;
use DI\Container;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Loader\FilesystemLoader;

use function DI\autowire;

final class DefaultApp implements App
{
    private static $singleton;

    public static function singleton(): ContainerInterface
    {
        if (static::$singleton) {
            return static::$singleton;
        }
        return static::$singleton = (new static())->make();
    }

    public function make(array $config = []): ContainerInterface
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions([
            Pathfinder::class => autowire(DefaultPathfinder::class),
            ConfigParser::class => autowire(DefaultConfigParser::class),
            'injectedConfig' => $config,
            Config::class => function (Container $c) {

                $defaultConfig = (new DefaultConfig([]))->all();
                $loadedConfig = (new Config(
                    \Noodlehaus\Config::load([
                        '?' . $c->get(Pathfinder::class)->rootPath() . '/.vo-config.yml',
                    ])->all()
                ))->all();
                $injectedConfig = $c->get('injectedConfig');

                return $c->get(ConfigParser::class)->parse(new Config(array_merge_recursive(
                    $defaultConfig,
                    $loadedConfig,
                    $injectedConfig
                )));
            },
            VomlParser::class => function (Container $c) {
                return new DefaultVomlParser(new Parser(new Lexer()));
            },
            DefinitionLoader::class => function (Container $c) {
                return new VomlDefinitionLoader(
                    $c->get(VomlParser::class),
                    $c->get(Config::class)->get('definitionsRoot'),
                    $c->get(Config::class)->get('fileExtension')
                );
            },
            ExtensionHandler::class => function (Container $c) {
                return new DefaultExtensionHandler([]);
            },
            Registry::class => function (Container $c) {
                return new DefaultRegistry(
                    $c->get(DefinitionLoader::class)->load(),
                    $c->get(ExtensionHandler::class),
                    $c->get(TypeHandler::class),
                    $c->get(Renderer::class),
                    $c->get(Config::class)->get('namespace')
                );
            },
            Renderer::class => autowire(TwigRenderer::class),
            InternalEvaluator::class => function (Container $c) {
                return new DefaultInternalEvaluator(
                    $c->get(CodeStreamer::class),
                    $c->get(Registry::class),
                    $c->get(Config::class)->get('namespace')
                );
            },
            SetType::class => function (Container $c) {
                return new SetType($c->get(InternalEvaluator::class));
            },
            TypeHandler::class => function (Container $c) {
                return new DefaultTypeHandler(
                    $c,
                    [
                        CompositeType::class,
                        EntityType::class,
                        EnumType::class,
                        SetType::class,
                        EntitySetType::class,
                        GenericType::class,
                    ]
                );
            },
            TwigEnvironment::class => function (Container $c) {
                $twig = new TwigEnvironment(
                    new FilesystemLoader($c->get(Config::class)->get('templateDirs')),
                    []
                );
                $twig->addFilter((new UcfirstFilter())->make());
                $twig->addFilter((new ImplementsFilter())->make());
                return $twig;
            },
            CodeStreamer::class => function (Container $c) {
                return new DefaultCodeStreamer(
                    $c->get(Config::class)->get('namespace')
                );
            },
        ]);
        return $builder->build();
    }
}
