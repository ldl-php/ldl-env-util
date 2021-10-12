<?php declare(strict_types=1);

namespace LDL\Env\Util\Line\Collection\Compiler\Options;

use LDL\Framework\Base\Contracts\ArrayFactoryInterface;

class EnvCompilerOptions implements EnvCompilerOptionsInterface
{
    /**
     * @var bool
     */
    private $allowVariableOverwrite;

    /**
     * @var bool
     */
    private $addPrefix;

    /**
     * @var bool
     */
    private $varNameToUpperCase;

    /**
     * @var bool
     */
    private $commentsEnabled;

    /**
     * @var callable
     */
    private $onBeforeCompile;

    /**
     * @var callable
     */
    private $onCompile;

    /**
     * @var callable
     */
    private $onAfterCompile;

    private function __construct(
        bool $allowVariableOverwrite = false,
        bool $addPrefix = false,
        bool $varNameToUpperCase = true,
        bool $commentsEnabled = true,
        callable $onBeforeCompile = null,
        callable $onCompile = null,
        callable $onAfterCompile = null
    )
    {
        $this->allowVariableOverwrite = $allowVariableOverwrite;
        $this->addPrefix = $addPrefix;
        $this->varNameToUpperCase = $varNameToUpperCase;
        $this->commentsEnabled = $commentsEnabled;
        $this->onBeforeCompile=$onBeforeCompile;
        $this->onCompile=$onCompile;
        $this->onAfterCompile=$onAfterCompile;
    }

    /**
     * @param bool|null $useKeys
     * @return array
     */
    public function toArray(bool $useKeys=null) : array
    {
        return get_object_vars($this);
    }

    /**
     * @return array
     */
    public function jsonSerialize() : array
    {
        $vars = $this->toArray();

        unset($vars['onBeforeCompile'], $vars['onCompile'], $vars['onAfterCompile']);

        return $vars;
    }

    /**
     * @param array $options
     * @return EnvCompilerOptionsInterface
     */
    public static function fromArray(array $options=[]) : ArrayFactoryInterface
    {
        $k = 'array_key_exists';

        return new self(
            $k('allowVariableOverwrite', $options) ? (bool)$options['allowVariableOverwrite'] : false,
            $k('addPrefix', $options) ? (bool)$options['addPrefix'] : true,
            $k('varNameToUpperCase', $options) ? (bool)$options['varNameToUpperCase'] : true,
            $k('commentsEnabled', $options) ? (bool)$options['commentsEnabled'] : true,
            $k('onBeforeCompile', $options) && is_callable($options['onBeforeCompile']) ? $options['onBeforeCompile'] : null,
            $k('onCompile', $options) && is_callable($options['onCompile']) ? $options['onCompile'] : null,
            $k('onAfterCompile', $options) && is_callable($options['onAfterCompile']) ? $options['onAfterCompile'] : null
        );
    }

    /**
     * @return bool
     */
    public function isAllowVariableOverwrite(): bool
    {
        return $this->allowVariableOverwrite;
    }

    /**
     * @return bool
     */
    public function isAddPrefix(): bool
    {
        return $this->addPrefix;
    }

    /**
     * @return bool
     */
    public function isVarNameToUpperCase(): bool
    {
        return $this->varNameToUpperCase;
    }

    /**
     * @return bool
     */
    public function isCommentsEnabled(): bool
    {
        return $this->commentsEnabled;
    }

    /**
     * @return callable|null
     */
    public function getOnBeforeCompile() : ?callable
    {
        return $this->onBeforeCompile;
    }

    /**
     * @return callable|null
     */
    public function getOnCompile() : ?callable
    {
        return $this->onCompile;
    }

    /**
     * @return callable|null
     */
    public function getOnAfterCompile() : ?callable
    {
        return $this->onAfterCompile;
    }

    /**
     * @param EnvCompilerOptionsInterface $options
     * @return EnvCompilerOptionsInterface
     * @throws \LDL\Framework\Base\Exception\ToArrayException
     */
    public function merge(EnvCompilerOptionsInterface $options) : ArrayFactoryInterface
    {
        return self::fromArray(
            array_merge($options->toArray(), $this->toArray())
        );
    }
}