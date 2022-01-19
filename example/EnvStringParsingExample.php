<?php

declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use LDL\Env\Util\Compiler\EnvCompiler;
use LDL\Env\Util\Parser\EnvParser;
use LDL\Framework\Base\Collection\CallableCollection;

$parser = new EnvParser(null,
    (new CallableCollection())->append(static function (string $line) {
        echo "Parse line $line\n";
    })
);

$lines = [
    '!LDL-COMPILER START={"onDuplicateVar":"last"}',
    'DUPLICATE_VAR=FIRST',
    'DUPLICATE_VAR=MID',
    'DUPLICATE_VAR=LAST',
    '!LDL-COMPILER STOP',
    '!LDL-COMPILER START={"VAR_NAME_CASE": "UPPER"}',
    'App_Admin_URL=(string)http://localhost:8080',
    'MAINTENANCE_MODE=0',
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
    '!LDL-COMPILER STOP',
];

$lines = $parser->parse($lines);

foreach ($lines as $line) {
    dump(sprintf('%s = %s', get_class($line), $line));
}

$compiler = new EnvCompiler();

var_dump($compiler->compile($lines)->getStringCollection()->filterEmptyLines()->implode("\n"));
