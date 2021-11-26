<?php declare(strict_types=1);

namespace LDL\Env\Util\File\Parser\Options;

use LDL\Framework\Base\Collection\CallableCollectionInterface;

interface EnvFileParserOptionsInterface
{
    /**
     * @return bool
     */
    public function isSkipUnreadable(): bool;

    /**
     * @return int
     */
    public function getDirPrefixDepth(): int;

    /**
     * @return CallableCollectionInterface|null
     */
    public function getOnBeforeParse() : ?CallableCollectionInterface;
}