<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\Config;

use PHPUnit\Framework\TestCase;

final class DefaultConfigTest extends TestCase
{
    public function test_default_config_loads()
    {
        $config = new DefaultConfig([]);
        $this->assertEquals([
            'namespace' => 'ValueObjects',
            'templateDirs' => ['{packagePath}/templates'],
            'definitionsRoot' => '{rootPath}',
            'fileExtension' => 'voml',
            'enrichments' => ['{rootPath}/enrichments'],
        ], $config->all());
    }
}
