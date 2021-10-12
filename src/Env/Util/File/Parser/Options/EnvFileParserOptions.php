<?php declare(strict_types=1);

namespace LDL\Env\Util\File\Parser\Options;

use LDL\Framework\Base\Contracts\ArrayFactoryInterface;

class EnvFileParserOptions implements EnvFileParserOptionsInterface
{
    /**
     * @var bool
     */
    private $skipUnreadable;

    /**
     * @var bool
     */
    private $ignoreSyntaxErrors;

    /**
     * @var int
     */
    private $dirPrefixDepth;

    /**
     * @var bool
     */
    private $ignore;

    public function __construct(
        bool $skipUnreadable=false,
        bool $ignoreSyntaxErrors=false,
        int $dirPrefixDepth=2,
        bool $ignore=false
    )
    {
        $this->skipUnreadable = $skipUnreadable;
        $this->ignoreSyntaxErrors = $ignoreSyntaxErrors;
        $this->dirPrefixDepth = $dirPrefixDepth;
        $this->ignore = $ignore;
    }

    public function toArray(bool $useKeys=null) : array
    {
        return get_object_vars($this);
    }

    public function jsonSerialize() : array
    {
        return $this->toArray();
    }

    /**
     * @param array $options
     * @return EnvFileParserOptionsInterface
     */
    public static function fromArray(array $options=[]) : ArrayFactoryInterface
    {
        return new self(
            array_key_exists('skipUnreadable', $options) ? (bool)$options['skipUnreadable'] : false,
            array_key_exists('ignoreSyntaxErrors', $options) ? (bool)$options['ignoreSyntaxErrors'] : false,
            array_key_exists('dirPrefixDepth', $options) ? (int)$options['dirPrefixDepth'] : 2,
            array_key_exists('ignore', $options) ? (bool)$options['ignore'] : false
        );
    }

    public function isSkipUnreadable() : bool
    {
        return $this->skipUnreadable;
    }

    public function isIgnoreSyntaxErrors() : bool
    {
        return $this->ignoreSyntaxErrors;
    }

    public function getDirPrefixDepth() : int
    {
        return $this->dirPrefixDepth;
    }

    public function isIgnore(): bool
    {
        return $this->ignore;
    }

    /**
     * @param EnvFileParserOptionsInterface $options
     * @return EnvFileParserOptionsInterface
     * @throws \LDL\Framework\Base\Exception\ToArrayException
     */
    public function merge(EnvFileParserOptionsInterface $options): ArrayFactoryInterface
    {
        return self::fromArray(
            array_merge($options->toArray(), $this->toArray())
        );
    }
}
