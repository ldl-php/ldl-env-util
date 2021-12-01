<?php declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use LDL\Env\Util\Compiler\Collection\EnvCompilerDirectiveCollection;
use LDL\Env\Util\Line\Type\Directive\Factory\EnvLineDirectiveFactory;
use LDL\Env\Util\Line\Parser\Directive\EnvLineCompilerDirectiveParser;

/**
 * This will contain all directives by default
 */
$directives = new EnvCompilerDirectiveCollection();

echo "Create collection containing default directives (with default parameters):\n\n";

foreach($directives as $d){
    dump(get_class($d));
}

echo "\nCreate a string from the previous collection of directives:\n\n";
$directive = EnvLineDirectiveFactory::create($directives);

dump(get_class($directive));

echo "\nPrint the directive:\n\n";
dump($directive->getString());

echo "Obtain available directives from directive as objects:\n\n";

$directives = EnvLineDirectiveFactory::getDirectives($directive);

echo "Remove first directive (Skip empty):\n\n";
$directives->removeByKey(0);

echo "Recreate directive and dump as string, as we removed the first directive, skip empty must not be present\n";
$directive = EnvLineDirectiveFactory::create($directives);

dump((string) $directive);