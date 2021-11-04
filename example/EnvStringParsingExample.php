<?php declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use LDL\Env\Util\Compiler\EnvCompiler;
use LDL\Env\Util\Compiler\Options\EnvCompilerOptions;
use LDL\Env\Util\File\Writer\EnvFileWriter;
use LDL\Env\Util\File\Writer\Options\EnvFileWriterOptions;
use LDL\Env\Util\Parser\EnvParser;
use LDL\Env\Util\Line\Parser\EnvLineParserCollection;
use LDL\Env\Util\Line\Parser\Comment\EnvLineCommentParser;
use LDL\Env\Util\Line\Parser\Directive\EnvLineCompilerDirectiveParser;
use LDL\Env\Util\Line\Parser\EmptyLine\EnvEmptyLineParser;
use LDL\Env\Util\Line\Parser\Variable\EnvLineVarParser;

echo "Create Parser Collection\n";

$parserCollection = new EnvLineParserCollection([
    new EnvLineCommentParser(),
    new EnvLineCompilerDirectiveParser(),
    new EnvEmptyLineParser(),
    new EnvLineVarParser()
]);

$parser = new EnvParser();

$lines = [
    '!LDL-COMPILER START={"onDuplicateVar":"last"}',
    'DUPLICATE_VAR=FIRST',
    'DUPLICATE_VAR=MID',
    'DUPLICATE_VAR=LAST',
    '!LDL-COMPILER STOP',
    '!LDL-COMPILER START={"VAR_NAME_CASE": "UPPER"}',
    'App_Admin_URL=(string)http://localhost:8080',
    'MAINTENANCE_MODE=(int)0',
    '',
    'lowercase_must_be_uppercase=1',
    '!LDL-COMPILER STOP',
    '!LDL-COMPILER START={"varNameToUpperCase": false,"comments":false}',
    'lowercase_must_remain_lowercase=1',
    '#COMMENT LINE',
    '!LDL-COMPILER STOP',
    'UNKNOWN LINE',
    '!LDL-COMPILER START={"ignore": true}',
    'MUST_NOT_BE_SHOWN=1',
    'MUST_NOT_BE_SHOWN=2',
    '!LDL-COMPILER STOP'
];

echo "Lines to be parsed:\n";
echo var_export($lines,true)."\n\n";

echo "Parse lines:\n";
$lines = $parser->parse($lines);

foreach($lines as $line){
    dump(sprintf('%s = %s', get_class($line), $line));
}

$compiler = new EnvCompiler();

var_dump($compiler->compile($lines)->filterEmptyLines()->implode("\n"));