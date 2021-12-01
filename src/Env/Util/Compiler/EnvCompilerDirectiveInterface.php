<?php declare(strict_types=1);

/**
 * The compiler unlike the parser takes in a collection of lines for post processing, the parser is in charge
 * of parsing each line, while the compiler is in charge of processing directives such as finding duplicate lines.
 */

namespace LDL\Env\Util\Compiler;

use LDL\Env\Util\Line\Collection\EnvLineCollectionInterface;
use LDL\Env\Util\Line\EnvLineInterface;
use LDL\Env\Util\Line\Type\Directive\EnvLineDirectiveInterface;
use LDL\Framework\Base\Contracts\Type\ToArrayInterface;

interface EnvCompilerDirectiveInterface extends ToArrayInterface
{
    public function compile(
        EnvLineInterface $currentLine,
        EnvLineCollectionInterface $lines,
        EnvLineCollectionInterface $curLines,
        EnvLineDirectiveInterface $directive
    ) : ?EnvLineInterface;

    /**
     * Determines if this EnvCompilerDirectiveInterface matches the passed EnvLineDirectiveInterface
     *
     * @param EnvLineDirectiveInterface $directive
     * @return bool
     */
    public function matches(EnvLineDirectiveInterface $directive) : bool;

    /**
     * @param array $options
     * @return EnvCompilerDirectiveInterface|null
     */
    public static function fromOptions(array $options) : ?EnvCompilerDirectiveInterface;
}