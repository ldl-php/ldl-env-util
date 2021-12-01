<?php declare(strict_types=1);

namespace LDL\Env\Util\Line\Type\Directive\Factory;

use LDL\Env\Util\Line\Type\Directive\EnvLineDirectiveInterface;

interface EnvLineDirectiveFactoryInterface
{

    /**
     * Creates an EnvLineDirectiveInterface instance from a set of directives
     *
     * @param bool $isStart
     * @param iterable|null $directives
     * @return EnvLineDirectiveInterface
     */
    public static function create(bool $isStart, ?iterable $directives) : EnvLineDirectiveInterface;

}