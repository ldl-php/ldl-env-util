<?php declare(strict_types=1);

namespace LDL\Env\Util\Line\Type\Directive;

use LDL\Env\Util\Line\Collection\Compiler\Options\EnvCompilerOptionsInterface;
use LDL\Env\Util\Line\EnvLineInterface;
use LDL\Env\Util\File\Parser\Options\EnvFileParserOptionsInterface;

interface EnvLineDirectiveInterface extends EnvLineInterface
{
    public const ENV_DIRECTIVE_STRING='!LDL-ENV';

    public function getCompilerOptions() : ?EnvCompilerOptionsInterface;

    public function getParserOptions() : ?EnvFileParserOptionsInterface;

    public function parse(array $matches): void;
}