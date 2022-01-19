<?php

declare(strict_types=1);

namespace LDL\Env\Util\Compiler\Collection;

use LDL\Env\Util\Compiler\Directive\EnvDuplicateVarResolverCompilerDirective;
use LDL\Env\Util\Compiler\Directive\EnvIgnoreCommentsCompilerDirective;
use LDL\Env\Util\Compiler\Directive\EnvIgnoreLineCompilerDirective;
use LDL\Env\Util\Compiler\Directive\EnvPrefixLengthCompilerDirective;
use LDL\Env\Util\Compiler\Directive\EnvSkipEmptyCompilerDirective;
use LDL\Env\Util\Compiler\Directive\EnvUnknownLineCompilerDirective;
use LDL\Env\Util\Compiler\Directive\EnvVarCaseTransformCompilerDirective;
use LDL\Env\Util\Compiler\EnvCompilerDirectiveInterface;
use LDL\Env\Util\Line\Collection\EnvLineCollectionInterface;
use LDL\Env\Util\Line\EnvLineInterface;
use LDL\Env\Util\Line\Type\Directive\EnvLineDirectiveInterface;
use LDL\Type\Collection\AbstractTypedCollection;
use LDL\Validators\InterfaceComplianceValidator;

class EnvCompilerDirectiveCollection extends AbstractTypedCollection implements EnvCompilerDirectiveCollectionInterface
{
    public function __construct(iterable $items = null)
    {
        $this->getAppendValueValidatorChain()
            ->getChainItems()
            ->append(new InterfaceComplianceValidator(EnvCompilerDirectiveInterface::class))
            ->lock();

        if (null === $items) {
            $items = [
                new EnvSkipEmptyCompilerDirective(),
                new EnvVarCaseTransformCompilerDirective(),
                new EnvIgnoreLineCompilerDirective(),
                new EnvIgnoreCommentsCompilerDirective(),
                new EnvDuplicateVarResolverCompilerDirective(),
                new EnvUnknownLineCompilerDirective(),
                new EnvPrefixLengthCompilerDirective(),
            ];
        }

        parent::__construct($items);
    }

    public function compile(
        EnvLineInterface $currentLine,
        EnvLineCollectionInterface $lines,
        EnvLineCollectionInterface $curLines,
        EnvLineDirectiveInterface $directive
    ): ?EnvLineInterface {
        $result = null;
        /*
         * @var EnvCompilerDirectiveInterface $compiler
         */
        foreach ($this as $_directive) {
            $result = $_directive->compile($result ?? $currentLine, $lines, $curLines, $directive);
            if (null === $result) {
                return null;
            }
        }

        return $result;
    }
}
