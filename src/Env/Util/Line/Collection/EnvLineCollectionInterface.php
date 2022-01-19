<?php

declare(strict_types=1);

namespace LDL\Env\Util\Line\Collection;

use LDL\Env\Util\Line\EnvLineInterface;
use LDL\Env\Util\Line\Type\Variable\EnvLineVarInterface;
use LDL\Framework\Base\Contracts\Type\ToStringInterface;
use LDL\Type\Collection\TypedCollectionInterface;
use LDL\Type\Collection\Types\String\StringCollection;

interface EnvLineCollectionInterface extends TypedCollectionInterface, ToStringInterface
{
    public function getVar(string $name): ?EnvLineInterface;

    public function hasVar(string $variable): bool;

    public function countVar(string $variable): int;

    public function replaceVar(EnvLineVarInterface $var): EnvLineCollectionInterface;

    /**
     * Merges a line collection inside of another collection.
     */
    public function merge(EnvLineCollectionInterface $lines): EnvLineCollectionInterface;

    public function getStringCollection(): StringCollection;

    /**
     * Filters EnvLineVarInterface objects and loads them into the system by using
     * $_ENV super global and putenv.
     */
    public function load(): void;
}
