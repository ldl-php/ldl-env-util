<?php declare(strict_types=1);

namespace LDL\Env\Util\Line\Type\Directive;

use LDL\Env\Util\Compiler\Options\EnvCompilerOptionsInterface;
use LDL\Env\Util\Line\Type\AbstractEnvLine;

class EnvLineDirective extends AbstractEnvLine implements EnvLineDirectiveInterface
{
    /**
     * @var EnvCompilerOptionsInterface|null
     */
    private $compilerOptions;

    /**
     * @var bool
     */
    private $isStart;

    public function __construct(
        string $line,
        bool $isStart,
        array $compilerOptions = null
    )
    {
        parent::__construct($line);
        $this->isStart = $isStart;
        $this->compilerOptions = $compilerOptions;
    }

    public function isStart() : bool
    {
        return $this->isStart;
    }

    public function getCompilerOptions() : array
    {
        return $this->compilerOptions;
    }

}