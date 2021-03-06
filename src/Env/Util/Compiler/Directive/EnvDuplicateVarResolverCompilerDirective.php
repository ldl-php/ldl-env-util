<?php declare(strict_types=1);

namespace LDL\Env\Util\Compiler\Directive;

use LDL\Env\Util\Compiler\EnvCompilerDirectiveInterface;
use LDL\Env\Util\Line\Collection\EnvLineCollectionInterface;
use LDL\Env\Util\Line\EnvLineInterface;
use LDL\Env\Util\Line\Type\Directive\EnvLineDirectiveInterface;
use LDL\Env\Util\Line\Type\Variable\EnvLineVarInterface;

class EnvDuplicateVarResolverCompilerDirective implements EnvCompilerDirectiveInterface
{
    public const DIRECTIVE='ONDUPLICATEVAR';

    public const THROW='throw';
    public const USE_LAST='last';
    public const USE_FIRST='first';

    /**
     * @var string
     */
    private $action;

    public function __construct(string $action=self::THROW)
    {
        $this->action = $action;
    }

    public function compile(
        EnvLineInterface $line,
        EnvLineCollectionInterface $lines,
        EnvLineCollectionInterface $curLines,
        EnvLineDirectiveInterface $directive
    ): ?EnvLineInterface
    {
        $options = array_change_key_case($directive->getCompilerOptions(), \CASE_UPPER);

        if(!$line instanceof EnvLineVarInterface || !array_key_exists(self::DIRECTIVE, $options)){
            return $line;
        }

        /**
         * If the variable is not present, just return the line
         */
        if(!$curLines->hasVar($line->getVar())){
            return $line;
        }

        /**
         * Case which var should prevail, first, last or throw
         */
        switch($options[self::DIRECTIVE]){
            case self::USE_FIRST:
                return null;
            break;

            case self::USE_LAST:
                $curLines->replaceVar($line);
                return null;
            break;

            case self::THROW:
                throw new \LogicException("Duplicate variable {$line}");
            break;

            default:
                return $line;
            break;
        }

    }

    public function matches(EnvLineDirectiveInterface $directive): bool
    {
        $options = array_change_key_case($directive->getCompilerOptions(), \CASE_UPPER);
        return array_key_exists(self::DIRECTIVE, $options);
    }

    public static function fromOptions(array $options): ?EnvCompilerDirectiveInterface
    {
        $options = array_change_key_case($options, \CASE_UPPER);
        if(!array_key_exists(self::DIRECTIVE, $options)){
            return null;
        }

        return new self($options[self::DIRECTIVE]);
    }

    public function toArray(bool $useKeys = null): array
    {
        return [
          self::DIRECTIVE => $this->action
        ];
    }

}
