<?php

declare(strict_types=1);

namespace LDL\Env\Util\File\Parser;

use LDL\Env\Util\File\Exception\ReadEnvFileException;
use LDL\Env\Util\File\Parser\Exception\EnvFileParseException;
use LDL\Env\Util\Line\Collection\EnvLineCollectionInterface;
use LDL\File\Collection\ReadableFileCollection;

interface EnvFileParserInterface
{
    /**
     * @param ReadableFileCollection $files
     *
     * @throws EnvFileParseException
     * @throws ReadEnvFileException
     */
    public function parse(iterable $files, bool $prefixDirectory): EnvLineCollectionInterface;
}
