<?php declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use LDL\Env\Util\Line\Collection\EnvLineCollection;
use LDL\Env\Util\Line\Type\Comment\Parser\EnvLineCommentParser;
use LDL\Env\Util\Line\Type\Directive\Parser\EnvLineDirectiveParser;
use LDL\Env\Util\Line\Type\EmptyLine\Parser\EnvEmptyLineParser;
use LDL\Env\Util\Line\Type\Variable\Parser\EnvLineVarParser;

echo "Create line parsers\n";

$commentParser = new EnvLineCommentParser();
$directiveParser = new EnvLineDirectiveParser();
$emptyParser = new EnvEmptyLineParser();
$varParser = new EnvLineVarParser();

echo "Create COMMENT line\n";
$comment = $commentParser->createFromString('#START FILE');

echo "Create DIRECTIVE Line\n";
$directive = $directiveParser->createFromString('!LDL-ENV COMPILER={"varNameToUpperCase": true}');

echo "Create EMPTY line\n";
$empty = $emptyParser->createFromString('');

echo "Create VAR line\n";
$var = $varParser->createFromString('App_Admin_URL=(string)http://localhost:8080\r\n');

echo "Create another VAR line\n";
$var2 = $varParser->createFromString('MAINTENANCE_MODE=(int)0');

echo "Create EnvLineCollection\n";
$envLineCollection = new EnvLineCollection();

echo "Add lines to EnvLineCollection\n";
$envLineCollection->appendMany([
   $comment,
   $directive,
   $empty,
   $var,
   $var2
]);

echo "Check items in EnvLineCollection\n";
foreach($envLineCollection as $line){
    echo "Line: ".$line->getString()."\n";
}

try{
    echo "EnvLineCollection toArray\n";
    var_dump($envLineCollection->toArray());
    echo "OK!\n";
}catch(\Exception $e){
    echo "EXCEPTION: {$e->getMessage()}\n";
}

echo "Change (replace) MAINTENANCE_MODE 0 to 1\n";
$envLineCollection->replaceVar($varParser->createFromString('MAINTENANCE_MODE=(int)1'));

try{
    echo "EnvLineCollection toArray\n";
    var_dump($envLineCollection->toArray());
    echo "OK!\n";
}catch(\Exception $e){
    echo "EXCEPTION: {$e->getMessage()}\n";
}
