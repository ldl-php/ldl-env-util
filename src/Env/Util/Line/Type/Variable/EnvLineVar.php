<?php declare(strict_types=1);

namespace LDL\Env\Util\Line\Type\Variable;

use LDL\Env\Util\Line\Type\AbstractEnvLine;
use Symfony\Component\String\UnicodeString;

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
     * @var mixed
     */
    private $castedValue;

    /**
     * @var string|null
     */
    private $prefix;

    public function __construct(
        string $string,
        string $var,
        string $value,
        string $prefix = ''
    )
    {
        parent::__construct($string);
        $this->var = $var;
        $this->value = $value;
        $this->prefix = $prefix;
    }

    public function getVar(bool $withPrefix = true) : string
    {
        if('' === $this->prefix){
            return $this->var;
        }

        return true === $withPrefix ? sprintf('%s%s', $this->prefix, $this->var) : $this->var;
    }

    public function getValue(bool $cast=true)
    {
        if(false === $cast){
            return $this->value;
        }

        if(false === preg_match('#^\(.*\)#', $this->value, $matches)){
            return $this->value;
        }


        if(count($matches) === 0){
            return $this->value;
        }

        if(null !== $this->castedValue){
            return $this->castedValue;
        }

        $castTo = $matches[0];
        $castValue = mb_substr($this->value, mb_strlen($castTo));

        switch($castTo){
            case '(int)':
                return $this->castedValue = (int)$castValue;

            case '(bool)':
                return $this->castedValue = (bool)$castValue;

            case '(double)':
            case '(float)':
                return $this->castedValue = (double)$castValue;
        }

        return $this->castedValue = $castValue;
    }

    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    public function toUpperCase(): EnvLineVarInterface
    {
        return new self(
            $this->getString(),
            (new UnicodeString($this->var))->upper()->toString(),
            $this->value,
            (new UnicodeString($this->prefix))->upper()->toString(),
        );
    }

    public function __toString(): string
    {
        return sprintf('%s=%s', $this->getVar(true), $this->getValue());
    }
}