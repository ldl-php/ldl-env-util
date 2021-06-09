<?php declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use LDL\Env\Util\File\Parser\EnvFileParser;
use LDL\Env\Util\Line\Collection\Compiler\EnvCompiler;
use LDL\Env\Util\File\Writer\EnvFileWriter;
use LDL\Env\Util\File\Writer\Options\EnvFileWriterOptions;
use LDL\File\Collection\ReadableFileCollection;

$files = new ReadableFileCollection([
    sprintf('%s/Application/Admin/.env', __DIR__),
    sprintf('%s/Application/User/.env', __DIR__)
]);

$parser = new EnvFileParser();
$compiler = new EnvCompiler();

$writer = new EnvFileWriter(
    EnvFileWriterOptions::fromArray([
        'filename' => '.env-compiled',
        'force' => true
    ])
);

try{
    echo "Parse env files\n";
    $parsedLineCollection = $parser->parse($files);

    echo "Compile parsed files\n";
    $compiledLines = $compiler->compile($parsedLineCollection);

    echo "Write compiled file\n";
    $writer->write($compiledLines, $writer->getOptions()->getFilename());
    echo "[ STATUS: Finished OK!]\n";

}catch(\Exception $e) {

    echo $e->getMessage()."\n";
    echo "[ Build failed! ]\n";
    return;

}

