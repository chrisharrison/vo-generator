<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\Config;

use Noodlehaus\AbstractConfig;

final class DefaultConfig extends AbstractConfig
{
    protected function getDefaults()
    {
        return [
            'namespace' => 'ValueObjects',
            'templateDirs' => ['{packagePath}/templates'],
            'definitionsRoot' => '{rootPath}',
            'fileExtension' => 'voml',
            'enrichments' => ['{rootPath}/enrichments'],
        ];
    }
}
