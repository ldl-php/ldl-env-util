<?php declare(strict_types=1);

namespace LDL\Env\Util\Compiler\Collection;

use LDL\Env\Util\Compiler\EnvCompilerInterface;
use LDL\Env\Util\Compiler\Type\EnvDuplicateVarResolver;
use LDL\Env\Util\Compiler\Type\EnvIgnoreComments;
use LDL\Env\Util\Compiler\Type\EnvIgnoreLine;
use LDL\Env\Util\Compiler\Type\EnvSkipEmpty;
use LDL\Env\Util\Compiler\Type\EnvUnknownLineResolver;
use LDL\Env\Util\Compiler\Type\EnvVarCaseTransform;
use LDL\Env\Util\Line\Collection\EnvLineCollectionInterface;
use LDL\Env\Util\Line\EnvLineInterface;
use LDL\Env\Util\Line\Type\Directive\EnvCompilerDirectiveInterface;
use LDL\Type\Collection\AbstractTypedCollection;
use LDL\Validators\InterfaceComplianceValidator;

class EnvCompilerCollection extends AbstractTypedCollection implements EnvCompilerInterface
{
    public function __construct(iterable $items = null)
    {
        $this->getAppendValueValidatorChain()
            ->getChainItems()
            ->append(new InterfaceComplianceValidator(EnvCompilerInterface::class))
            ->lock();

        if(null === $items){
            $items = [
                new EnvSkipEmpty(),
                new EnvVarCaseTransform(),
                new EnvIgnoreLine(),
                new EnvIgnoreComments(),
                new EnvDuplicateVarResolver(),
                new EnvUnknownLineResolver()
            ];
        }

        parent::__construct($items);
    }

    public function compile(
        EnvLineInterface $currentLine,
        EnvLineCollectionInterface $lines,
        EnvLineCollectionInterface $curLines,
        EnvCompilerDirectiveInterface $directive
    ): ?EnvLineInterface
    {
        $result = null;
        /**
         * @var EnvCompilerInterface $compiler
         */
        foreach($this as $compiler) {
            $result = $compiler->compile($result ?? $currentLine, $lines, $curLines, $directive);
            if(null === $result){
                return null;
            }
        }

        return $result;
    }
}
