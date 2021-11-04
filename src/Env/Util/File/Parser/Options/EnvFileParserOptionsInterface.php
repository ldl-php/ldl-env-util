<?php declare(strict_types=1);

namespace LDL\Env\Util\File\Parser\Options;

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

}