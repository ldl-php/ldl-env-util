<?php declare(strict_types=1);

namespace LDL\Env\Util\File\Writer\Options;

use LDL\Framework\Base\Contracts\ArrayFactoryInterface;
use LDL\Framework\Base\Contracts\ToArrayInterface;

interface EnvFileWriterOptionsInterface extends ArrayFactoryInterface, ToArrayInterface, \JsonSerializable
{
    /**
     * @return string
     */
    public function getFilename(): string;

    /**
     * @return bool
     */
    public function isForce(): bool;

    /**
     * @param EnvFileWriterOptionsInterface $options
     * @return EnvFileWriterOptionsInterface
     */
    public function merge(EnvFileWriterOptionsInterface $options);
}