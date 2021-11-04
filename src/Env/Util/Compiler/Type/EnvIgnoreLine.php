<?php declare(strict_types=1);

namespace LDL\Env\Util\Compiler\Type;

use LDL\Env\Util\Compiler\EnvCompilerInterface;
use LDL\Env\Util\Line\Collection\EnvLineCollectionInterface;
use LDL\Env\Util\Line\EnvLineInterface;
use LDL\Env\Util\Line\Type\Directive\EnvCompilerDirectiveInterface;
use LDL\Env\Util\Line\Type\Variable\EnvLineVar;
use LDL\Env\Util\Line\Type\Variable\EnvLineVarInterface;

class EnvIgnoreLine implements EnvCompilerInterface
{
    public const DIRECTIVE='IGNORE';

    public function getOptions() : array
    {
        return [];
    }

    public function compile(
        EnvLineInterface $line,
        EnvLineCollectionInterface $lines,
        EnvLineCollectionInterface $curLines,
        EnvCompilerDirectiveInterface $directive
    ): ?EnvLineInterface
    {
        $options = array_change_key_case($directive->getCompilerOptions(), \CASE_UPPER);
        if(!$line instanceof EnvLineVarInterface || !array_key_exists(self::DIRECTIVE, $options)){
            return $line;
        }

        $ignore = (bool) $options[self::DIRECTIVE];

        return $ignore ? null : $line;
    }

}
