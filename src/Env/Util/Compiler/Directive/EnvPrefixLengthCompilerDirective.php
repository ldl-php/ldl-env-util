<?php

declare(strict_types=1);

namespace LDL\Env\Util\Compiler\Directive;

use LDL\Env\Util\Compiler\EnvCompilerDirectiveInterface;
use LDL\Env\Util\Line\Collection\EnvLineCollectionInterface;
use LDL\Env\Util\Line\EnvLineInterface;
use LDL\Env\Util\Line\Type\Directive\EnvLineDirectiveInterface;
use LDL\Env\Util\Line\Type\Variable\EnvLineVar;
use LDL\Env\Util\Line\Type\Variable\EnvLineVarInterface;

class EnvPrefixLengthCompilerDirective implements EnvCompilerDirectiveInterface
{
    public const DIRECTIVE = 'PREFIX_LENGTH';

    /**
     * @var int
     */
    private $length;

    public function __construct(int $length = 1)
    {
        $this->length = $length;
    }

    public function compile(
        EnvLineInterface $line,
        EnvLineCollectionInterface $lines,
        EnvLineCollectionInterface $curLines,
        EnvLineDirectiveInterface $directive
    ): ?EnvLineInterface {
        $options = array_change_key_case($directive->getCompilerOptions(), \CASE_UPPER);
        $length = array_key_exists(self::DIRECTIVE, $options) ? (int) $options[self::DIRECTIVE] : $this->length;

        if (
            !$line instanceof EnvLineVarInterface ||
            null === $line->getPrefix() ||
            null === $line->getPrefixSeparator()
        ) {
            return $line;
        }

        if (0 === $length) {
            return new EnvLineVar(
                (string) $line,
                $line->getVar(),
                $line->getValue(),
                null,
                null
            );
        }

        $prefix = explode($line->getPrefixSeparator(), $line->getPrefix());

        $prefixLen = count($prefix);
        $length = $length >= $prefixLen ? $prefixLen - 1 : $length;
        $prefix = implode('_', array_slice($prefix, count($prefix) - $length));
        $regex = preg_quote($line->getPrefixSeparator(), '#');
        /**
         * Prefix normalization.
         */
        $prefix = preg_replace("#$regex#", '_', $prefix);

        return new EnvLineVar(
            (string) $line,
            $line->getVar(),
            $line->getValue(),
            $prefix,
            '_'
        );
    }

    public function matches(EnvLineDirectiveInterface $directive): bool
    {
        $options = array_change_key_case($directive->getCompilerOptions(), \CASE_UPPER);

        return array_key_exists(self::DIRECTIVE, $options);
    }

    public static function fromOptions(array $options): ?EnvCompilerDirectiveInterface
    {
        $options = array_change_key_case($options, \CASE_UPPER);

        if (!array_key_exists(self::DIRECTIVE, $options)) {
            return null;
        }

        return new self($options[self::DIRECTIVE]);
    }

    public function toArray(bool $useKeys = null): array
    {
        return [
          self::DIRECTIVE => $this->length,
        ];
    }
}
