<?php declare(strict_types=1);

namespace LDL\Env\Util\Line\Type\EmptyLine\Parser;

use LDL\Env\Util\Line\EnvLineInterface;
use LDL\Env\Util\Line\Type\EmptyLine\EnvEmptyLine;
use Symfony\Component\String\UnicodeString;

class EnvEmptyLineParser implements EnvEmptyLineParserInterface
{
    public function createFromString(string $line='') : ?EnvLineInterface
    {
        if('' === $line){
            return new EnvEmptyLine();
        }

        $line = (new UnicodeString($line))->trim()->toString();

        if('' !== $line){
            return null;
        }

        return new EnvEmptyLine();
    }
}