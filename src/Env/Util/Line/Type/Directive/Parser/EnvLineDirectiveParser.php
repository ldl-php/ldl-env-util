<?php declare(strict_types=1);

namespace LDL\Env\Util\Line\Type\Directive\Parser;

use LDL\Env\Util\Line\EnvLineInterface;
use LDL\Env\Util\Line\Type\Directive\EnvDirective;
use LDL\Env\Util\Line\Type\Directive\EnvLineDirectiveInterface;
use Symfony\Component\String\UnicodeString;

class EnvLineDirectiveParser implements EnvLineDirectiveParserInterface
{
    public function createFromString(string $line) : ?EnvLineInterface
    {
        $isDirective = (new UnicodeString($line))->trim()
                ->truncate(mb_strlen(EnvLineDirectiveInterface::ENV_DIRECTIVE_STRING))
                ->toString() === EnvLineDirectiveInterface::ENV_DIRECTIVE_STRING;

        if(!$isDirective){
            return null;
        }

        $instance = new EnvDirective($line);

        preg_match_all("/(\w+)=\{[^\}]*\}/", $line, $matches);

        if(count($matches) === 0){
            return $instance;
        }

        $instance->parse($matches[0]);

        return $instance;
    }
}