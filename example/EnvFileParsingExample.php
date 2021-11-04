<?php declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use LDL\Env\Util\File\Parser\EnvFileParser;
use LDL\Env\Util\Compiler\EnvCompiler;
use LDL\File\Collection\ReadableFileCollection;

$files = new ReadableFileCollection([
    sprintf('%s/Application/Admin/.env', __DIR__),
    sprintf('%s/Application/User/.env', __DIR__),
    sprintf('%s/Application/.env', __DIR__),
]);

$parser = new EnvFileParser();
$compiler = new EnvCompiler();

$file = sprintf('%s/%s', __DIR__, '.env-compiled');

try{
    echo "Parse env files:\n";

    $lines = $parser->parse($files);

    foreach($compiler->compile($lines) as $line){
        echo "$line\n";
    }

}catch(\Exception $e) {

    echo "[ Build failed! ]\n";
    echo $e->getMessage()."\n";

    echo $e->getTraceAsString()."\n";
    return;

}

