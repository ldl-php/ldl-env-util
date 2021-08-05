<?php declare(strict_types=1);

namespace LDL\Env\Util\Line\Parser;

use LDL\Env\Util\Line\EnvLineInterface;
use LDL\Type\Collection\Traits\Validator\AppendKeyValidatorChainTrait;
use LDL\Type\Collection\Traits\Validator\AppendValueValidatorChainTrait;
use LDL\Type\Collection\Types\Object\ObjectCollection;
use LDL\Type\Collection\Validator\UniqueValidator;
use LDL\Validators\IntegerValidator;
use LDL\Validators\InterfaceComplianceValidator;

class EnvLineParserCollection extends ObjectCollection implements EnvLineParserCollectionInterface
{
    use AppendValueValidatorChainTrait;
    use AppendKeyValidatorChainTrait;

    public function __construct(iterable $items = null)
    {
        parent::__construct($items);
        $this->getAppendValueValidatorChain()
            ->getChainItems()
            ->append(new InterfaceComplianceValidator(EnvLineParserInterface::class))
            ->lock();

        $this->getAppendKeyValidatorChain()
            ->getChainItems()
            ->appendMany([
                new IntegerValidator(),
                new UniqueValidator()
            ])
            ->lock();
    }

    public function parse(string $line): ?EnvLineInterface
    {
        /**
         * @var EnvLineParserInterface $parser
         */
        foreach($this as $parser){
            $env = $parser->createFromString($line);

            if(null === $env){
                continue;
            }

            return $env;
        }

        return null;
    }
}