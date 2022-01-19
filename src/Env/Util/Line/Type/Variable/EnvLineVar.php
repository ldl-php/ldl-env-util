<?php

declare(strict_types=1);

namespace LDL\Env\Util\Line\Type\Variable;

use LDL\Env\Util\Line\Type\AbstractEnvLine;

class EnvLineVar extends AbstractEnvLine implements EnvLineVarInterface
{
    /**
     * @var string
     */
    private $var;

    /**
     * @var string
     */
    private $value;

    /**
     * @var string|null
     */
    private $prefix;

    /**
     * @var string|null
     */
    private $prefixSeparator;

    public function __construct(
        string $string,
        string $var,
        string $value,
        string $prefix = null,
        string $prefixSeparator = null
    ) {
        parent::__construct($string);
        $this->var = $var;
        $this->value = trim($value, "\r\n");
        $this->prefix = $prefix;
        $this->prefixSeparator = $prefixSeparator;
    }

    public function getVar(): string
    {
        return $this->var;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    public function getPrefixSeparator(): ?string
    {
        return $this->prefixSeparator;
    }

    public function __toString(): string
    {
        return sprintf('%s%s%s=%s', $this->getPrefix(), $this->getPrefixSeparator(), $this->getVar(), $this->getValue());
    }
}
