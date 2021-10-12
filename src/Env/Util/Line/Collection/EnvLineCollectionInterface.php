<?php declare(strict_types=1);

namespace LDL\Env\Util\Line\Collection;

use LDL\Env\Util\Line\Type\Variable\EnvLineVarInterface;
use LDL\Framework\Base\Collection\Contracts\ReplaceByKeyInterface;
use LDL\Type\Collection\Interfaces\Validation\HasAppendKeyValidatorChainInterface;

interface EnvLineCollectionInterface extends HasAppendKeyValidatorChainInterface, ReplaceByKeyInterface, \Stringable
{
    /**
     * @param string $variable
     * @return bool
     */
    public function hasVar(string $variable): bool;

    /**
     * @param EnvLineVarInterface $var
     * @return EnvLineCollectionInterface
     */
    public function replaceVar(EnvLineVarInterface $var) : EnvLineCollectionInterface;
}