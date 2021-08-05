<?php declare(strict_types=1);

namespace LDL\Env\Util\Line\Collection;

use LDL\Env\Util\Line\EnvLineInterface;
use LDL\Env\Util\Line\Type\Variable\EnvLineVarInterface;
use LDL\Framework\Base\Collection\Contracts\CollectionInterface;
use LDL\Framework\Base\Collection\Traits\ReplaceableInterfaceTrait;
use LDL\Type\Collection\Traits\Validator\AppendKeyValidatorChainTrait;
use LDL\Type\Collection\Traits\Validator\AppendValueValidatorChainTrait;
use LDL\Type\Collection\Types\Object\ObjectCollection;
use LDL\Type\Collection\Validator\UniqueValidator;
use LDL\Validators\IntegerValidator;
use LDL\Validators\InterfaceComplianceValidator;

class EnvLineCollection extends ObjectCollection implements EnvLineCollectionInterface
{
    use AppendValueValidatorChainTrait;
    use AppendKeyValidatorChainTrait;
    use ReplaceableInterfaceTrait;

    /**
     * @var array
     */
    private $vars = [];

    public function __construct(iterable $items = null)
    {
        parent::__construct($items);
        $this->getAppendValueValidatorChain()
            ->getChainItems()
            ->append(new InterfaceComplianceValidator(EnvLineInterface::class))
            ->lock();

        $this->getAppendKeyValidatorChain()
            ->getChainItems()
            ->appendMany([
                new IntegerValidator(),
                new UniqueValidator()
            ])
            ->lock();
    }

    public function append($line, $key = null): CollectionInterface
    {
        if($line instanceof EnvLineVarInterface){
            $this->vars[$line->getVar()] = $line->getValue();
        }

        return parent::append($line);
    }

    public function remove($key): CollectionInterface
    {
        $line = $this->get($key);

        if($line instanceof EnvLineVarInterface){
            unset($this->vars[$line->getVar()]);
        }

        return parent::remove($key);
    }

    public function hasVar(string $variable): bool
    {
        return array_key_exists($variable, $this->vars);
    }

    public function replaceVar(EnvLineVarInterface $var) : EnvLineCollectionInterface
    {
        foreach($this as $k => $line){
            if(!$line instanceof EnvLineVarInterface){
                continue;
            }

            if($line->getVar() === $var->getVar()){
                $this->vars[$line->getVar()] = $var->getValue();
                $this->setItem($var, $k);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return implode(\PHP_EOL,\iterator_to_array($this));
    }

}