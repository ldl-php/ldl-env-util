<?php declare(strict_types=1);

namespace LDL\Env\Util\File\Parser;

use LDL\Env\Util\Line\Collection\EnvLineCollectionInterface;
use LDL\Env\Util\Parser\EnvParserInterface;
use LDL\File\Collection\ReadableFileCollection;

interface EnvFileParserInterface extends EnvParserInterface
{
    /**
     * @param ReadableFileCollection $files
     * @return EnvLineCollectionInterface
     */
    public function parse(iterable $files) : EnvLineCollectionInterface;

    /**
     * @return Options\EnvFileParserOptionsInterface
     */
    public function getOptions() : Options\EnvFileParserOptionsInterface;
}