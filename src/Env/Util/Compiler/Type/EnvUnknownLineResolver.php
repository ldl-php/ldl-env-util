<?php declare(strict_types=1);

namespace LDL\Env\Util\Compiler\Type;

use LDL\Env\Util\Compiler\EnvCompilerInterface;
use LDL\Env\Util\Line\Collection\EnvLineCollectionInterface;
use LDL\Env\Util\Line\EnvLineInterface;
use LDL\Env\Util\Line\Type\Comment\EnvLineCommentInterface;
use LDL\Env\Util\Line\Type\Directive\EnvCompilerDirectiveInterface;
use LDL\Env\Util\Line\Type\EnvUnknownLine;
use LDL\Env\Util\Line\Type\Variable\EnvLineVar;
use LDL\Env\Util\Line\Type\Variable\EnvLineVarInterface;

class EnvUnknownLineResolver implements EnvCompilerInterface
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
        EnvCompilerDirectiveInterface $directive
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
