<?php declare(strict_types=1);

/**
 * The compiler unlike the parser takes in a collection of lines for post processing, the parser is in charge
 * of parsing each line, while the compiler is in charge of processing directives such as finding duplicate lines.
 */

namespace LDL\Env\Util\Compiler;

use LDL\Env\Util\Line\Collection\EnvLineCollectionInterface;
use LDL\Env\Util\Line\EnvLineInterface;
use LDL\Env\Util\Line\Type\Directive\EnvCompilerDirectiveInterface;

interface EnvCompilerInterface
{
    public function compile(
        EnvLineInterface $currentLine,
        EnvLineCollectionInterface $lines,
        EnvLineCollectionInterface $curLines,
        EnvCompilerDirectiveInterface $directive
    ) : ?EnvLineInterface;
}