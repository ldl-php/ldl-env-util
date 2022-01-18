<?php

declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use LDL\Env\Util\Compiler\EnvCompiler;
use LDL\Env\Util\File\Parser\EnvFileParser;
use LDL\Env\Util\Loader\EnvLoader;
use LDL\File\Collection\ReadableFileCollection;
use LDL\Framework\Base\Collection\CallableCollection;

$files = new ReadableFileCollection([
    sprintf('%s/Application/Admin/.env', __DIR__),
    sprintf('%s/Application/User/.env', __DIR__),
    sprintf('%s/Application/.env', __DIR__),
]);

$parser = new EnvFileParser(null,
    false,
    (new CallableCollection())->append(static function ($file) {
        echo "$file ...\n";
    })
);

$compiler = new EnvCompiler();

$file = sprintf('%s/%s', __DIR__, '.env-compiled');

try {
    echo "Parse env files:\n";

    $lines = $parser->parse($files);

    echo "\n";

    $compiled = $compiler->compile($lines);

    foreach ($compiled as $line) {
        echo "$line\n";
    }

    /*
     * Load the lines into environment
     */
    EnvLoader::load($compiled);

    dump(getenv('APPLICATION_DEV_MODE'));
} catch (\Exception $e) {
    echo "[ Build failed! ]\n";
    echo $e->getMessage()."\n";

    echo $e->getTraceAsString()."\n";

    return;
}
