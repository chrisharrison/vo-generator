<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\App;

use ChrisHarrison\VoGenerator\CodeStreamer\CodeStreamer;
use ChrisHarrison\VoGenerator\CodeStreamer\DefaultCodeStreamer;
use ChrisHarrison\VoGenerator\ConfigBuilder\ConfigBuilder;
use ChrisHarrison\VoGenerator\ConfigBuilder\DefaultConfigBuilder;
use ChrisHarrison\VoGenerator\ConfigLoader\ConfigLoader;
use ChrisHarrison\VoGenerator\ConfigLoader\YamlConfigLoader;
use ChrisHarrison\VoGenerator\ConfigLoader\YmlConfigLoader;
use ChrisHarrison\VoGenerator\DefinitionLoader\DefinitionLoader;
use ChrisHarrison\VoGenerator\DefinitionLoader\YamlDefinitionLoader\YamlDefinitionLoader;
use ChrisHarrison\VoGenerator\Extension\NonNullExtension;
use ChrisHarrison\VoGenerator\ExtensionHandler\DefaultExtensionHandler;
use ChrisHarrison\VoGenerator\ExtensionHandler\ExtensionHandler;
use ChrisHarrison\VoGenerator\RegistryIterator\DefaultRegistryIterator;
use ChrisHarrison\VoGenerator\RegistryIterator\RegistryIterator;
use ChrisHarrison\VoGenerator\Renderer\Renderer;
use ChrisHarrison\VoGenerator\Renderer\TwigRenderer;
use ChrisHarrison\VoGenerator\Registry\DefaultRegistry;
use ChrisHarrison\VoGenerator\Registry\Registry;
use ChrisHarrison\VoGenerator\Type\DefaultTypes\BooleanType;
use ChrisHarrison\VoGenerator\Type\DefaultTypes\CompositeType;
use ChrisHarrison\VoGenerator\Type\DefaultTypes\EnumType;
use ChrisHarrison\VoGenerator\Type\DefaultTypes\FloatType;
use ChrisHarrison\VoGenerator\Type\DefaultTypes\IntegerType;
use ChrisHarrison\VoGenerator\Type\DefaultTypes\SetType;
use ChrisHarrison\VoGenerator\Type\DefaultTypes\StringType;
use ChrisHarrison\VoGenerator\TypeHandler\DefaultTypeHandler;
use ChrisHarrison\VoGenerator\TypeHandler\TypeHandler;
use Composer\Factory;
use DI\Container;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Loader\FilesystemLoader;

use function DI\autowire;

final class DefaultApp
{
    private static $singleton;

    public static function singleton(): ContainerInterface
    {
        if (static::$singleton) {
            return static::$singleton;
        }
        return static::$singleton = static::make();
    }

    public static function make(array $config = []): ContainerInterface
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions([
            'rootPath' => dirname(Factory::getComposerFile()),
            'packageRootPath' => function (Container $c) {
                $installedPath = $c->get('rootPath') . '/vendor/chrisharrison/vo-generator';
                if (file_exists($installedPath)) {
                    return $installedPath;
                }
                return $c->get('rootPath');
            },
            'injectedConfig' => $config,
            'config' => function (Container $c) {
                return $c->get(ConfigBuilder::class)->build();
            },
            ConfigBuilder::class => function (Container $c) {
                return new DefaultConfigBuilder(
                    $c->get(ConfigLoader::class),
                    $c->get('rootPath'),
                    $c->get('packageRootPath'),
                    $c->get('injectedConfig')
                );
            },
            ConfigLoader::class => function (Container $c) {
                return new YamlConfigLoader($c->get('rootPath') . '/.vo-config.yml');
            },
            DefinitionLoader::class => function (Container $c) {
                return new YamlDefinitionLoader(
                    $c->get(TypeHandler::class),
                    $c->get('config')['definitionFileRoot']
                );
            },
            ExtensionHandler::class => function (Container $c) {
                return new DefaultExtensionHandler([
                    new NonNullExtension(),
                ]);
            },
            Registry::class => function (Container $c) {
                return new DefaultRegistry(
                    $c->get(DefinitionLoader::class)->load(),
                    $c->get(ExtensionHandler::class),
                    $c->get(TypeHandler::class),
                    $c->get(Renderer::class)
                );
            },
            Renderer::class => autowire(TwigRenderer::class),
            TypeHandler::class => function (Container $c) {
                return new DefaultTypeHandler([
                    new BooleanType(),
                    new CompositeType(),
                    new EnumType(),
                    new FloatType(),
                    new IntegerType(),
                    new SetType(),
                    new StringType(),
                ]);
            },
            TwigEnvironment::class => function (Container $c) {
                return new TwigEnvironment(
                    new FilesystemLoader($c->get('config')['templateDirs']),
                    []
                );
            },
            CodeStreamer::class => function (Container $c) {
                return new DefaultCodeStreamer(
                    $c->get('config')['namespace']
                );
            },
        ]);
        return $builder->build();
    }
}
