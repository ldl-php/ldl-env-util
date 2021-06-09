<?php declare(strict_types=1);

namespace LDL\Env\Util\Line\Type\Variable;

use LDL\Env\Util\Line\EnvLineInterface;

interface EnvLineVarInterface extends EnvLineInterface
{
    /**
     * @return string|null
     */
    public function getPrefix(): ?string;

    /**
     * @param bool $withPrefix
     * @return string
     */
    public function getVar(bool $withPrefix = true) : string;

    /**
     * @param bool $cast
     * @return mixed
     */
    public function getValue(bool $cast=true);

    /**
     * @return EnvLineVarInterface
     */
    public function toUpperCase() : EnvLineVarInterface;
}