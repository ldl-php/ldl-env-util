<?php declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use LDL\Env\Util\Line\Collection\Compiler\EnvCompiler;
use LDL\Env\Util\Line\Collection\Compiler\Options\EnvCompilerOptions;
use LDL\Env\Util\File\Writer\EnvFileWriter;
use LDL\Env\Util\File\Writer\Options\EnvFileWriterOptions;
use LDL\Env\Util\Line\Collection\EnvLineCollection;
use LDL\Env\Util\Line\Parser\EnvLineParserCollection;
use LDL\Env\Util\Line\Type\Comment\Parser\EnvLineCommentParser;
use LDL\Env\Util\Line\Type\Directive\Parser\EnvLineDirectiveParser;
use LDL\Env\Util\Line\Type\EmptyLine\Parser\EnvEmptyLineParser;
use LDL\Env\Util\Line\Type\Variable\Parser\EnvLineVarParser;

echo "Create Parser Collection\n";

$parserCollection = new EnvLineParserCollection(
    new EnvLineParserCollection([
        new EnvLineCommentParser(),
        new EnvLineDirectiveParser(),
        new EnvEmptyLineParser(),
        new EnvLineVarParser()
    ])
);

echo "Create some lines\n";
$lines = [
    'App_Admin_URL=(string)http://localhost:8080',
    'MAINTENANCE_MODE=(int)0',
    '',
    '#COMMENT LINE',
    '!LDL-ENV PARSER={"ignore": true}',
    'THIS_VARIABLE_MUST_NOT_BE_VISIBLE=Administration'
];

echo "Create EnvLineCollection\n";
$envLineCollection = new EnvLineCollection();

echo "Parse each string line and added to EnvLineCollection\n";
foreach($lines as $line){
    $parsedLine = $parserCollection->parse($line);
    $envLineCollection->append($parsedLine);
}

echo "Create EnvCompiler with option 'isVarNameToUpperCase: false'\n";
$compiler = new EnvCompiler(
    EnvCompilerOptions::fromArray([
        'varNameToUpperCase' => false
    ])
);

echo "Compile EnvLineCollection\n";
$compiledLines = $compiler->compile($envLineCollection);

echo "Create EnvWriter\n";
$writer = new EnvFileWriter(
    EnvFileWriterOptions::fromArray([
        'filename' => '.env-compiled',
        'force' => true
    ])
);

try{
    echo "Write an .env-compiled file\n";
    $writer->write($compiledLines, $writer->getOptions()->getFilename());
    echo "OK!\n";
}catch(\Exception $e){
    echo "EXCEPTION: {$e->getMessage()}\n";
}
