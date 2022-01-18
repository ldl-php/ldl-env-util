<?php

declare(strict_types=1);

namespace LDL\Env\Util\Line\Parser;

use LDL\Env\Util\Line\EnvLineInterface;
use LDL\Env\Util\Line\Parser\Comment\EnvLineCommentParser;
use LDL\Env\Util\Line\Parser\Directive\EnvLineCompilerDirectiveParser;
use LDL\Env\Util\Line\Parser\EmptyLine\EnvEmptyLineParser;
use LDL\Env\Util\Line\Parser\Variable\EnvLineVarParser;
use LDL\Type\Collection\AbstractTypedCollection;
use LDL\Type\Collection\Traits\Validator\AppendKeyValidatorChainTrait;
use LDL\Type\Collection\Traits\Validator\AppendValueValidatorChainTrait;
use LDL\Type\Collection\Validator\UniqueValidator;
use LDL\Validators\IntegerValidator;
use LDL\Validators\InterfaceComplianceValidator;

class EnvLineParserCollection extends AbstractTypedCollection implements EnvLineParserCollectionInterface
{
    use AppendValueValidatorChainTrait;
    use AppendKeyValidatorChainTrait;

    public function __construct(iterable $items = null)
    {
        /*
         * If no items are passed, use default line parsers
         */
        if (null === $items) {
            $items = [
                new EnvLineCommentParser(),
                new EnvLineCompilerDirectiveParser(),
                new EnvEmptyLineParser(),
                new EnvLineVarParser(),
            ];
        }

        $this->getAppendValueValidatorChain()
            ->getChainItems()
            ->append(new InterfaceComplianceValidator(EnvLineParserInterface::class))
            ->lock();

        $this->getAppendKeyValidatorChain()
            ->getChainItems()
            ->appendMany([
                new IntegerValidator(),
                new UniqueValidator(),
            ])
            ->lock();

        parent::__construct($items);
    }

    public function parse(string $line, string $prefix = null, string $prefixSeparator = null): ?EnvLineInterface
    {
        /**
         * @var EnvLineParserInterface $parser
         */
        foreach ($this as $parser) {
            $env = $parser->createFromString($line, $prefix, $prefixSeparator);

            if (null === $env) {
                continue;
            }

            return $env;
        }

        return null;
    }
}
