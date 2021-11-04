<?php declare(strict_types=1);

namespace LDL\Env\Util\Compiler\Options;

use LDL\Framework\Base\Collection\CallableCollectionInterface;
use LDL\Framework\Base\Contracts\ArrayFactoryInterface;
use LDL\Framework\Base\Contracts\Type\ToArrayInterface;

interface EnvCompilerOptionsInterface extends ArrayFactoryInterface, ToArrayInterface, \JsonSerializable
{
    /**
     * @return bool
     */
    public function isAllowVariableOverwrite(): bool;

    /**
     * @return bool
     */
    public function isAddPrefix(): bool;

    /**
     * @return bool
     */
    public function isVarNameToUpperCase(): bool;

    /**
     * @return bool
     */
    public function isCommentsEnabled(): bool;

    /**
     * @return CallableCollectionInterface
     */
    public function getOnBeforeCompile(): CallableCollectionInterface;

    /**
     * @return CallableCollectionInterface
     */
    public function getOnCompile(): CallableCollectionInterface;

    /**
     * @return CallableCollectionInterface
     */
    public function getOnAfterCompile(): CallableCollectionInterface;

    /**
     * @param EnvCompilerOptionsInterface $options
     * @return EnvCompilerOptionsInterface
     */
    public function merge(EnvCompilerOptionsInterface $options);
}