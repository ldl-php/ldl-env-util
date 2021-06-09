<?php declare(strict_types=1);

namespace LDL\Env\Util\File\Parser;

use LDL\Env\Util\Line\Collection\EnvLineCollectionInterface;
use LDL\File\Collection\ReadableFileCollection;

interface EnvFileParserInterface
{
    /**
     * @param ReadableFileCollection $files
     * @param Options\EnvFileParserOptionsInterface $options
     * @return EnvLineCollectionInterface
     */
    public function parse(
        ReadableFileCollection $files,
        Options\EnvFileParserOptionsInterface $options=null
    ) : EnvLineCollectionInterface;

    /**
     * @return Options\EnvFileParserOptionsInterface
     */
    public function getOptions() : Options\EnvFileParserOptionsInterface;
}