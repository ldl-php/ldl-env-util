<?php declare(strict_types=1);

namespace LDL\Env\Util\Line\Type;

use LDL\Env\Util\Line\EnvLineInterface;

abstract class AbstractEnvLine implements EnvLineInterface
{
    /**
     * @var string
     */
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function getString(): string
    {
        return $this->value;
    }

    public function __toString() : string
    {
        return $this->value;
    }
}