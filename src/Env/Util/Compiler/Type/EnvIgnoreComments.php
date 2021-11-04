<?php declare(strict_types=1);

namespace LDL\Env\Util\Compiler\Type;

use LDL\Env\Util\Compiler\EnvCompilerInterface;
use LDL\Env\Util\Line\Collection\EnvLineCollectionInterface;
use LDL\Env\Util\Line\EnvLineInterface;
use LDL\Env\Util\Line\Type\Comment\EnvLineCommentInterface;
use LDL\Env\Util\Line\Type\Directive\EnvCompilerDirectiveInterface;

class EnvIgnoreComments implements EnvCompilerInterface
{
    public const DIRECTIVE='COMMENTS';

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

        if(!$line instanceof EnvLineCommentInterface || !array_key_exists(self::DIRECTIVE, $options)){
            return $line;
        }

        return !$options[self::DIRECTIVE] ? null : $line;
    }

}
