<?php declare(strict_types=1);

namespace LDL\Env\Util\Line\Type\Directive\Factory;

use LDL\Env\Util\Compiler\Collection\EnvCompilerDirectiveCollectionInterface;
use LDL\Env\Util\Line\Type\Directive\EnvLineDirectiveInterface;

interface EnvLineDirectiveFactoryInterface
{

    /**
     * Creates an EnvLineDirectiveInterface start directive from a set of directives
     *
     * @param iterable|null $directives
     * @return EnvLineDirectiveInterface
     */
    public static function createStart(?iterable $directives) : EnvLineDirectiveInterface;

    /**
     * Creates an EnvLineDirectiveInterface stop directive
     *
     * @return EnvLineDirectiveInterface
     */
    public static function createStop() : EnvLineDirectiveInterface;

    /**
     * Obtains all applicable directives from an EnvLineDirectiveInterface object
     *
     * @param EnvLineDirectiveInterface $line
     * @param iterable|null $directives
     * @return EnvCompilerDirectiveCollectionInterface
     */
    public static function getDirectives(
        EnvLineDirectiveInterface $line,
        iterable $directives=null
    ) : EnvCompilerDirectiveCollectionInterface;

}