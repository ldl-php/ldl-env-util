<?php declare(strict_types=1);

namespace LDL\Env\Util\File\Parser\Options;

use LDL\Framework\Base\Contracts\ArrayFactoryInterface;
use LDL\Framework\Base\Contracts\ToArrayInterface;

interface EnvFileParserOptionsInterface extends ArrayFactoryInterface, ToArrayInterface, \JsonSerializable
{
    /**
     * @return bool
     */
    public function isSkipUnreadable() : bool;

    /**
     * @return bool
     */
    public function isIgnoreSyntaxErrors() : bool;

    /**
     * @return int
     */
    public function getDirPrefixDepth() : int;

    /**
     * @return bool
     */
    public function isIgnore(): bool;

    /**
     * @param EnvFileParserOptionsInterface $options
     * @return EnvFileParserOptionsInterface
     */
    public function merge(EnvFileParserOptionsInterface $options);
}