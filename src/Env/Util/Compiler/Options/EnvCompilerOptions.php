<?php declare(strict_types=1);

namespace LDL\Env\Util\Compiler\Options;

use LDL\Framework\Base\Collection\CallableCollection;
use LDL\Framework\Base\Collection\CallableCollectionInterface;
use LDL\Framework\Base\Contracts\ArrayFactoryInterface;
use LDL\Framework\Helper\IterableHelper;

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
     * @var CallableCollectionInterface
     */
    private $onBeforeCompile;

    /**
     * @var CallableCollectionInterface
     */
    private $onCompile;

    /**
     * @var CallableCollectionInterface
     */
    private $onAfterCompile;

    private function __construct(
        bool $allowVariableOverwrite = false,
        bool $addPrefix = false,
        bool $varNameToUpperCase = true,
        bool $commentsEnabled = true,
        CallableCollectionInterface $onBeforeCompile = null,
        CallableCollectionInterface $onCompile = null,
        CallableCollectionInterface $onAfterCompile = null
    )
    {
        $this->allowVariableOverwrite = $allowVariableOverwrite;
        $this->addPrefix = $addPrefix;
        $this->varNameToUpperCase = $varNameToUpperCase;
        $this->commentsEnabled = $commentsEnabled;
        $this->onBeforeCompile = $onBeforeCompile ?? new CallableCollection();
        $this->onCompile = $onCompile ?? new CallableCollection();
        $this->onAfterCompile = $onAfterCompile ?? new CallableCollection();
    }

    public function toArray(bool $useKeys=null) : array
    {
        return get_object_vars($this);
    }

    public function jsonSerialize() : array
    {
        $vars = $this->toArray();

        /**
         * Filter all values which are not a callable collection
         */
        IterableHelper::filter($this, static function($i){
            return !$i instanceof CallableCollectionInterface;
        });

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
            $k('onBeforeCompile', $options) && $options['onBeforeCompile'] instanceof CallableCollectionInterface ? $options['onBeforeCompile'] : null,
            $k('onCompile', $options) && $options['onCompile'] instanceof CallableCollectionInterface ? $options['onCompile'] : null,
            $k('onAfterCompile', $options) && $options['onAfterCompile'] instanceof CallableCollectionInterface ? $options['onAfterCompile'] : null
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
     * @return CallableCollectionInterface
     */
    public function getOnBeforeCompile() : CallableCollectionInterface
    {
        return $this->onBeforeCompile;
    }

    /**
     * @return CallableCollectionInterface
     */
    public function getOnCompile() : CallableCollectionInterface
    {
        return $this->onCompile;
    }

    /**
     * @return CallableCollectionInterface
     */
    public function getOnAfterCompile() : CallableCollectionInterface
    {
        return $this->onAfterCompile;
    }

    /**
     * @param EnvCompilerOptionsInterface $options
     * @return EnvCompilerOptionsInterface
     */
    public function merge(EnvCompilerOptionsInterface $options) : ArrayFactoryInterface
    {
        return self::fromArray(
            array_merge($options->toArray(), $this->toArray())
        );
    }
}