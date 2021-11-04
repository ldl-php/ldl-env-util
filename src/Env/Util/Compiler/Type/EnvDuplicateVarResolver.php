<?php declare(strict_types=1);

namespace LDL\Env\Util\Compiler\Type;

use LDL\Env\Util\Compiler\EnvCompilerInterface;
use LDL\Env\Util\Line\Collection\EnvLineCollectionInterface;
use LDL\Env\Util\Line\EnvLineInterface;
use LDL\Env\Util\Line\Type\Comment\EnvLineCommentInterface;
use LDL\Env\Util\Line\Type\Directive\EnvCompilerDirectiveInterface;
use LDL\Env\Util\Line\Type\Variable\EnvLineVar;
use LDL\Env\Util\Line\Type\Variable\EnvLineVarInterface;

class EnvDuplicateVarResolver implements EnvCompilerInterface
{
    public const DIRECTIVE='ONDUPLICATEVAR';

    public const THROW='throw';
    public const USE_LAST='last';
    public const USE_FIRST='first';

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

        /**
         * If the variable is not present, just return the line
         */
        if(!$curLines->hasVar($line->getVar())){
            return $line;
        }

        /**
         * Case which var should prevail, first, last or throw
         */
        switch($options[self::DIRECTIVE]){
            case self::USE_FIRST:
                return null;
            break;

            case self::USE_LAST:
                $curLines->replaceVar($line);
                return null;
            break;

            case self::THROW:
                throw new \LogicException("Duplicate variable {$line}");
            break;

            default:
                return $line;
            break;
        }

    }

}
