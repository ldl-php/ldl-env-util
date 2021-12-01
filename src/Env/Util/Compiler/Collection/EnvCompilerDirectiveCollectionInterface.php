<?php declare(strict_types=1);

namespace LDL\Env\Util\Compiler\Collection;

use LDL\Env\Util\Line\Collection\EnvLineCollectionInterface;
use LDL\Env\Util\Line\EnvLineInterface;
use LDL\Env\Util\Line\Type\Directive\EnvLineDirectiveInterface;
use LDL\Type\Collection\TypedCollectionInterface;

interface EnvCompilerDirectiveCollectionInterface extends TypedCollectionInterface
{

    public function compile(
        EnvLineInterface $currentLine,
        EnvLineCollectionInterface $lines,
        EnvLineCollectionInterface $curLines,
        EnvLineDirectiveInterface $directive
    ): ?EnvLineInterface;

}
