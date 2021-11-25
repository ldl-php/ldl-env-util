<?php declare(strict_types=1);

namespace LDL\Env\Util\Compiler\Directive;

use LDL\Env\Util\Compiler\EnvCompilerDirectiveInterface;
use LDL\Env\Util\Line\Collection\EnvLineCollectionInterface;
use LDL\Env\Util\Line\EnvLineInterface;
use LDL\Env\Util\Line\Type\Directive\EnvLineDirectiveInterface;
use LDL\Env\Util\Line\Type\EmptyLine\EnvEmptyLineInterface;

class EnvSkipEmptyCompilerDirective implements EnvCompilerDirectiveInterface
{
    public const DIRECTIVE='SKIP_EMPTY';

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

        if(!$line instanceof EnvEmptyLineInterface || !array_key_exists(self::DIRECTIVE, $options)){
            return $line;
        }

        $ignore = (bool) $options[self::DIRECTIVE];

        return $ignore ? null : $line;
    }

}
