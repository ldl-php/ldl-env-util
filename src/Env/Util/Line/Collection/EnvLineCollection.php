<?php

declare(strict_types=1);

namespace LDL\Env\Util\Line\Collection;

use LDL\Env\Util\Line\EnvLineInterface;
use LDL\Env\Util\Line\Type\Variable\EnvLineVarInterface;
use LDL\Env\Util\Loader\EnvLoader;
use LDL\Framework\Base\Collection\Contracts\CollectionInterface;
use LDL\Framework\Helper\IterableHelper;
use LDL\Type\Collection\AbstractTypedCollection;
use LDL\Type\Collection\Traits\Validator\AppendKeyValidatorChainTrait;
use LDL\Type\Collection\Traits\Validator\AppendValueValidatorChainTrait;
use LDL\Type\Collection\Types\String\StringCollection;
use LDL\Type\Collection\Validator\UniqueValidator;
use LDL\Validators\IntegerValidator;
use LDL\Validators\InterfaceComplianceValidator;

class EnvLineCollection extends AbstractTypedCollection implements EnvLineCollectionInterface
{
    use AppendValueValidatorChainTrait;
    use AppendKeyValidatorChainTrait;

    /**
     * @var array
     */
    private $vars = [];

    public function __construct(iterable $items = null)
    {
        $this->getAppendValueValidatorChain()
            ->getChainItems()
            ->append(new InterfaceComplianceValidator(EnvLineInterface::class))
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

    public function append($line, $key = null): CollectionInterface
    {
        if ($line instanceof EnvLineVarInterface) {
            $this->vars[$line->getVar()] = $line->getValue();
        }

        return parent::append($line);
    }

    public function remove($key): CollectionInterface
    {
        $line = $this->get($key);

        if ($line instanceof EnvLineVarInterface) {
            unset($this->vars[$line->getVar()]);
        }

        return parent::remove($key);
    }

    public function getVar(string $name): ?EnvLineInterface
    {
        if (!array_key_exists($name, $this->vars)) {
            return null;
        }

        foreach ($this as $line) {
            if (!$line instanceof EnvLineVarInterface) {
                continue;
            }

            if ($line->getVar() === $name) {
                return $line;
            }
        }

        return null;
    }

    public function hasVar(string $variable): bool
    {
        return array_key_exists($variable, $this->vars);
    }

    public function countVar(string $variable): int
    {
        $count = 0;

        foreach ($this as $k => $line) {
            if (!$line instanceof EnvLineVarInterface) {
                continue;
            }

            if ($line->getVar() === $variable) {
                $count++;
            }
        }

        return $count;
    }

    public function replaceVar(EnvLineVarInterface $var): EnvLineCollectionInterface
    {
        $this->replaceByCallback(static function ($v, $k) use ($var) {
            return $v->getVar() === $var->getVar();
        }, $var);

        return $this;
    }

    public function merge(EnvLineCollectionInterface $lines): EnvLineCollectionInterface
    {
        foreach ($lines as $line) {
            $this->append($line);
        }

        return $this;
    }

    public function load(): void
    {
        EnvLoader::load($this);
    }

    public function getStringCollection(): StringCollection
    {
        return new StringCollection(IterableHelper::map($this, static function ($v) {
            return (string) $v;
        }));
    }

    public function toString(): string
    {
        return implode(\PHP_EOL, \iterator_to_array($this));
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
