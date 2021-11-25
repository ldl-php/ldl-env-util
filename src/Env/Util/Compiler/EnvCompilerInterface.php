<?php declare(strict_types=1);

namespace LDL\Env\Util\Compiler;

use LDL\Env\Util\Line\Collection\EnvLineCollectionInterface;

interface EnvCompilerInterface
{
    /**
     * Compile an EnvLineCollection
     *
     * Iterates through $lines and applies compiler directives into each line.
     * Returns a new EnvLineCollection with all compiler directives applied.
     *
     * @param EnvLineCollectionInterface $lines
     * @return EnvLineCollectionInterface
     */
    public function compile(EnvLineCollectionInterface $lines) : EnvLineCollectionInterface;
}