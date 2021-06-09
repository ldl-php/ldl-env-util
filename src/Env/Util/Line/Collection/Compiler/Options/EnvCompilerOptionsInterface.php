<?php declare(strict_types=1);

namespace LDL\Env\Util\Line\Collection\Compiler\Options;

use LDL\Framework\Base\Contracts\ArrayFactoryInterface;
use LDL\Framework\Base\Contracts\ToArrayInterface;

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
     * @return callable|null
     */
    public function getOnBeforeCompile(): ?callable;

    /**
     * @return callable|null
     */
    public function getOnCompile(): ?callable;

    /**
     * @return callable|null
     */
    public function getOnAfterCompile(): ?callable;

    /**
     * @param EnvCompilerOptionsInterface $options
     * @return EnvCompilerOptionsInterface
     */
    public function merge(EnvCompilerOptionsInterface $options);
}