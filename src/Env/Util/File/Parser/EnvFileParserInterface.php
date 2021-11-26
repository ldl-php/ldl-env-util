<?php declare(strict_types=1);

namespace LDL\Env\Util\File\Parser;

use LDL\Env\Util\File\Exception\ReadEnvFileException;
use LDL\Env\Util\Line\Collection\EnvLineCollectionInterface;
use LDL\Env\Util\Parser\EnvParserInterface;
use LDL\File\Collection\ReadableFileCollection;

interface EnvFileParserInterface extends EnvParserInterface
{
    /**
     * @param ReadableFileCollection $files
     * @return EnvLineCollectionInterface
     * @throws ReadEnvFileException
     */
    public function parse(iterable $files) : EnvLineCollectionInterface;

}