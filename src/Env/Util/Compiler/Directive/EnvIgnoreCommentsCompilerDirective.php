<?php declare(strict_types=1);

namespace LDL\Env\Util\Compiler\Directive;

use LDL\Env\Util\Compiler\EnvCompilerDirectiveInterface;
use LDL\Env\Util\Line\Collection\EnvLineCollectionInterface;
use LDL\Env\Util\Line\EnvLineInterface;
use LDL\Env\Util\Line\Type\Comment\EnvLineCommentInterface;
use LDL\Env\Util\Line\Type\Directive\EnvLineDirectiveInterface;

class EnvIgnoreCommentsCompilerDirective implements EnvCompilerDirectiveInterface
{
    public const DIRECTIVE='COMMENTS';

    /**
     * @var bool
     */
    private $ignore;

    public function __construct(bool $ignore=false)
    {
        $this->ignore = $ignore;
    }

    public function compile(
        EnvLineInterface $line,
        EnvLineCollectionInterface $lines,
        EnvLineCollectionInterface $curLines,
        EnvLineDirectiveInterface $directive
    ): ?EnvLineInterface
    {
        $options = array_change_key_case($directive->getCompilerOptions(), \CASE_UPPER);

        if(!$line instanceof EnvLineCommentInterface || !array_key_exists(self::DIRECTIVE, $options)){
            return $line;
        }

        return !$options[self::DIRECTIVE] ? null : $line;
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
            self::DIRECTIVE=>$this->ignore
        ];
    }

}
