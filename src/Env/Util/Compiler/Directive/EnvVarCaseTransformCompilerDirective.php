<?php declare(strict_types=1);

namespace LDL\Env\Util\Compiler\Directive;

use LDL\Env\Util\Compiler\EnvCompilerDirectiveInterface;
use LDL\Env\Util\Line\Collection\EnvLineCollectionInterface;
use LDL\Env\Util\Line\EnvLineInterface;
use LDL\Env\Util\Line\Type\Directive\EnvLineDirectiveInterface;
use LDL\Env\Util\Line\Type\Variable\EnvLineVar;
use LDL\Env\Util\Line\Type\Variable\EnvLineVarInterface;

class EnvVarCaseTransformCompilerDirective implements EnvCompilerDirectiveInterface
{
    public const DIRECTIVE='VAR_NAME_CASE';
    public const CASE_UPPER='UPPER';
    public const CASE_LOWER='LOWER';

    /**
     * @var string
     */
    private $casing;

    public function __construct(string $casing=null){
        $this->casing = $casing;
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

        switch($options[self::DIRECTIVE]){
            case self::CASE_UPPER:
                return new EnvLineVar(
                    $line->getString(),
                    strtoupper($line->getVar(false)),
                    (string)$line->getValue(false),
                    strtoupper($line->getPrefix())
                );
                break;
            case self::CASE_LOWER:
                return new EnvLineVar(
                    $line->getString(),
                    strtolower($line->getVar(false)),
                    (string)$line->getValue(false),
                    strtolower($line->getPrefix())
                );
                break;

            default:
                return $line;
        }

    }
    public static function fromOptions(array $options): ?EnvCompilerDirectiveInterface
    {
        $options = array_change_key_case($options, \CASE_UPPER);
        if(!array_key_exists(self::DIRECTIVE, $options)){
            return null;
        }

        return new self($options[self::DIRECTIVE]);
    }

    public function matches(EnvLineDirectiveInterface $directive): bool
    {
        $options = array_change_key_case($directive->getCompilerOptions(), \CASE_UPPER);
        return array_key_exists(self::DIRECTIVE, $options);
    }

    public function toArray(bool $useKeys = null): array
    {
        return [
          self::DIRECTIVE => $this->casing
        ];
    }

}
