<?php declare(strict_types=1);

namespace LDL\Env\Util\Compiler\Directive;

use LDL\Env\Util\Compiler\EnvCompilerDirectiveInterface;
use LDL\Env\Util\Line\Collection\EnvLineCollectionInterface;
use LDL\Env\Util\Line\EnvLineInterface;
use LDL\Env\Util\Line\Type\Directive\EnvLineDirectiveInterface;
use LDL\Env\Util\Line\Type\EmptyLine\EnvEmptyLineInterface;

class EnvSkipEmptyCompilerDirective implements EnvCompilerDirectiveInterface
{
    public const DIRECTIVE='SKIP_EMPTY';

    /**
     * @var bool
     */
    private $skip;

    public function __construct(bool $skip=true)
    {
        $this->skip = $skip;
    }

    public function compile(
        EnvLineInterface $line,
        EnvLineCollectionInterface $lines,
        EnvLineCollectionInterface $curLines,
        EnvLineDirectiveInterface $directive
    ): ?EnvLineInterface
    {
        $options = array_change_key_case($directive->getCompilerOptions(), \CASE_UPPER);

        if(!$line instanceof EnvEmptyLineInterface || !array_key_exists(self::DIRECTIVE, $options)){
            return $line;
        }

        $ignore = (bool) $options[self::DIRECTIVE];

        return $ignore ? null : $line;
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
          self::DIRECTIVE => $this->skip
        ];
    }

}
