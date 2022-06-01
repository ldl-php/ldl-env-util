<?php

declare(strict_types=1);

namespace LDL\Env\Util\Line\Type\Variable;

use LDL\Env\Util\Line\EnvLineInterface;

interface EnvLineVarInterface extends EnvLineInterface
{
    public function getPrefix(): ?string;

    public function getPrefixSeparator(): ?string;

    public function getVar(bool $prefix): string;

    public function getValue(): string;
}
