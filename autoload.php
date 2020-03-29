<?php

declare(strict_types=1);

use ChrisHarrison\VoGenerator\App\DefaultApp;
use ChrisHarrison\VoGenerator\CodeStreamer\CodeStreamer;
use ChrisHarrison\VoGenerator\CodeStreamOut\Runtime;
use ChrisHarrison\VoGenerator\Registry\Registry;

spl_autoload_register(function ($className) {
    $app = DefaultApp::singleton();
    $code = $app->get(Registry::class)->resolve($className);
    if ($code === null) {
        return null;
    }
    $app->get(CodeStreamer::class)->stream(new ArrayIterator([$code]), new Runtime());
});
