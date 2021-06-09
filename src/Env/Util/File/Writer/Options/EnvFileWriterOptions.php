<?php declare(strict_types=1);

namespace LDL\Env\Util\File\Writer\Options;

use LDL\Framework\Base\Contracts\ArrayFactoryInterface;

class EnvFileWriterOptions implements EnvFileWriterOptionsInterface
{
    private const DEFAULT_FILENAME = '.env-compiled';

    /**
     * @var string
     */
    private $filename;

    /**
     * @var bool
     */
    private $force;

    public function __construct(
        bool $force,
        string $filename=null
    )
    {
        $this->force = $force;
        $this->filename = $filename ?? self::DEFAULT_FILENAME;
    }

    public function toArray() : array
    {
        return get_object_vars($this);
    }

    public function jsonSerialize() : array
    {
        return $this->toArray();
    }

    /**
     * @param array $options
     * @return EnvFileWriterOptionsInterface
     */
    public static function fromArray(array $options=[]) : ArrayFactoryInterface
    {
        return new self(
            $options['force'] ?? false,
            $options['filename'] ?? self::DEFAULT_FILENAME
        );
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @return bool
     */
    public function isForce(): bool
    {
        return $this->force;
    }

    /**
     * @param EnvFileWriterOptionsInterface $options
     * @return EnvFileWriterOptionsInterface
     * @throws \LDL\Framework\Base\Exception\ToArrayException
     */
    public function merge(EnvFileWriterOptionsInterface $options) : ArrayFactoryInterface
    {
        return self::fromArray(
            array_merge($options->toArray(), $this->toArray())
        );
    }
}