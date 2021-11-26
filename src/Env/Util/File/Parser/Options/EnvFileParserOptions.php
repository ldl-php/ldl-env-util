<?php declare(strict_types=1);

namespace LDL\Env\Util\File\Parser\Options;

use LDL\Framework\Base\Collection\CallableCollection;
use LDL\Framework\Base\Collection\CallableCollectionInterface;
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
     * @var CallableCollection
     */
    private $beforeParse;

    public function __construct(
        bool $skipUnreadable=false,
        int $dirPrefixDepth=2,
        CallableCollection $onBeforeParse=null
    )
    {
        $this->skipUnreadable = $skipUnreadable;
        $this->dirPrefixDepth = $dirPrefixDepth;
        $this->beforeParse = $onBeforeParse;
    }

    public function toArray(bool $useKeys=null) : array
    {
        $vars = get_object_vars($this);
        unset($vars['beforeParse']);
        return $vars;
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
            array_key_exists('dirPrefixDepth', $options) ? (int)$options['dirPrefixDepth'] : 2,
            array_key_exists('onBeforeParse', $options) ? new CallableCollection($options['onBeforeParse']) : null
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

    public function getOnBeforeParse() : ?CallableCollectionInterface
    {
        return $this->beforeParse;
    }

    public function isIgnore(): bool
    {
        return $this->ignore;
    }

    public function merge(EnvParserOptionsInterface $options): EnvParserOptionsInterface
    {
        return self::fromArray(
            array_merge($options->toArray(), $this->toArray())
        );
    }
}
