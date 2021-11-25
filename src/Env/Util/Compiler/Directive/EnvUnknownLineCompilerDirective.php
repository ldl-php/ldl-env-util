<?php declare(strict_types=1);

namespace LDL\Env\Util\Compiler\Directive;

use LDL\Env\Util\Compiler\EnvCompilerDirectiveInterface;
use LDL\Env\Util\Line\Collection\EnvLineCollectionInterface;
use LDL\Env\Util\Line\EnvLineInterface;
use LDL\Env\Util\Line\Type\Directive\EnvLineDirectiveInterface;
use LDL\Env\Util\Line\Type\EnvUnknownLine;

class EnvUnknownLineCompilerDirective implements EnvCompilerDirectiveInterface
{
    public const DIRECTIVE='ONUNKNOWLINE';

    public const THROW='throw';
    public const DISCARD_UNKNOWN_LINE='discard';

    public function getOptions() : array
    {
        return [];
    }

    public function compile(
        EnvLineInterface $line,
        EnvLineCollectionInterface $lines,
        EnvLineCollectionInterface $curLines,
        EnvLineDirectiveInterface $directive
    ): ?EnvLineInterface
    {
        $options = array_change_key_case($directive->getCompilerOptions(), \CASE_UPPER);

        /**
         * Discard unknown lines by default
         */
        if(!$line instanceof EnvUnknownLine){
            return $line;
        }

        if(!array_key_exists(self::DIRECTIVE, $options)){
            return null;
        }

        throw new \LogicException("Unknown line: $line");
    }

}
