<?php

declare(strict_types=1);

namespace LDL\Env\Util\Compiler;

use LDL\Env\Util\Compiler\Collection\EnvCompilerDirectiveCollection;
use LDL\Env\Util\Compiler\Directive\EnvPrefixLengthCompilerDirective;
use LDL\Env\Util\Compiler\Directive\EnvVarCaseTransformCompilerDirective;
use LDL\Env\Util\Line\Collection\EnvLineCollection;
use LDL\Env\Util\Line\Collection\EnvLineCollectionInterface;
use LDL\Env\Util\Line\Type\Directive\EnvLineDirectiveInterface;
use LDL\Env\Util\Line\Type\Directive\Factory\EnvLineDirectiveFactory;
use LDL\Env\Util\Line\Type\EnvUnknownLine;
use LDL\Framework\Base\Collection\CallableCollectionInterface;

final class EnvCompiler implements EnvCompilerInterface
{
    /**
     * @var array
     */
    private $compilers;

    /**
     * @var EnvLineDirectiveInterface|null
     */
    private $startDirective;

    /**
     * @var CallableCollectionInterface
     */
    private $beforeCompile;

    /**
     * @var CallableCollectionInterface
     */
    private $afterCompile;

    public function __construct(
        EnvCompilerDirectiveCollection $compilers = null,
        EnvLineDirectiveInterface $startDirective = null,
        CallableCollectionInterface $onBeforeCompile = null,
        CallableCollectionInterface $onAfterCompile = null
    ) {
        $this->compilers = $compilers ?? new EnvCompilerDirectiveCollection();

        $this->startDirective = $startDirective ?? EnvLineDirectiveFactory::createStart([
            new EnvPrefixLengthCompilerDirective(1),
            new EnvVarCaseTransformCompilerDirective(),
        ]);

        $this->beforeCompile = $onBeforeCompile;
        $this->afterCompile = $onAfterCompile;
    }

    /**
     * {@inheritdoc}
     */
    public function compile(EnvLineCollectionInterface $lines): EnvLineCollectionInterface
    {
        $curLines = new EnvLineCollection();
        $curDirective = $this->startDirective;

        foreach ($lines as $line) {
            if (null !== $this->beforeCompile) {
                $this->beforeCompile->call($line, $lines, $curLines, $curDirective);
            }

            $isDirective = $line instanceof EnvLineDirectiveInterface;

            /*
             * START directive
             */
            if ($isDirective && $line->isStart()) {
                $curDirective = $line;
                continue;
            }

            /*
             * STOP Directive, go back to original start directive
             */
            if ($isDirective && false === $line->isStart()) {
                $curDirective = $this->startDirective;
                continue;
            }

            $line = $curDirective ? $this->compilers->compile($line, $lines, $curLines, $curDirective) : $line;

            if (null !== $this->afterCompile) {
                $this->afterCompile->call($line, $lines, $curLines, $curDirective);
            }

            if (null === $line || $line instanceof EnvUnknownLine) {
                continue;
            }

            $curLines->append($line);
        }

        return $curLines;
    }
}
