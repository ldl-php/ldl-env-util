<?php declare(strict_types=1);

namespace LDL\Env\Util\Line\Collection\Compiler;

use LDL\Env\Util\Line\Collection\EnvLineCollectionInterface;

interface EnvCompilerInterface
{
    /**
     * @param EnvLineCollectionInterface $lines
     * @return EnvLineCollectionInterface
     */
    public function compile(EnvLineCollectionInterface $lines) : EnvLineCollectionInterface;

    /**
     * @return Options\EnvCompilerOptions
     */
    public function getOptions(): Options\EnvCompilerOptions;
}