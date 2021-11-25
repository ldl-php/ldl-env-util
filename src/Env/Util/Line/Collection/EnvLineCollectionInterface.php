<?php declare(strict_types=1);

namespace LDL\Env\Util\Line\Collection;

use LDL\Env\Util\Line\EnvLineInterface;
use LDL\Env\Util\Line\Type\Variable\EnvLineVarInterface;
use LDL\Framework\Base\Contracts\Type\ToStringInterface;
use LDL\Type\Collection\TypedCollectionInterface;
use LDL\Type\Collection\Types\String\StringCollection;

interface EnvLineCollectionInterface extends TypedCollectionInterface, ToStringInterface
{
    /**
     * @param string $name
     * @return EnvLineInterface|null
     */
    public function getVar(string $name) : ?EnvLineInterface;

    /**
     * @param string $variable
     * @return bool
     */
    public function hasVar(string $variable): bool;

    /**
     * @param string $variable
     * @return int
     */
    public function countVar(string $variable) : int;

    /**
     * @param EnvLineVarInterface $var
     * @return EnvLineCollectionInterface
     */
    public function replaceVar(EnvLineVarInterface $var) : EnvLineCollectionInterface;

    /**
     * Merges a line collection inside of another collection
     *
     * @param EnvLineCollectionInterface $lines
     * @return EnvLineCollectionInterface
     */
    public function merge(EnvLineCollectionInterface $lines) : EnvLineCollectionInterface;

    /**
     * @return StringCollection
     */
    public function getStringCollection() : StringCollection;
}