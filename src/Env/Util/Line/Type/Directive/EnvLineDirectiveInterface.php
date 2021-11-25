<?php declare(strict_types=1);

namespace LDL\Env\Util\Line\Type\Directive;

use LDL\Env\Util\Line\EnvLineInterface;

interface EnvLineDirectiveInterface extends EnvLineInterface
{
    public const ENV_COMPILER_STRING = '!LDL-COMPILER';
    public const ENV_COMPILER_START='START';
    public const ENV_COMPILER_STOP='STOP';

    public function getCompilerOptions() : array;

    public function isStart() : bool;
}