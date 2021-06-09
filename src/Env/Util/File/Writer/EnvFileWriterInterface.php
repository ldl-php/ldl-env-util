<?php declare(strict_types=1);

namespace LDL\Env\Util\File\Writer;

use LDL\Env\Util\Line\Collection\EnvLineCollectionInterface;

interface EnvFileWriterInterface
{
    /**
     * @param string $file
     * @param EnvLineCollectionInterface $envData
     * @throws \Exception
     */
    public function write(EnvLineCollectionInterface $envData, string $file): void;
    /**
     * @return Options\EnvFileWriterOptions
     */
    public function getOptions(): Options\EnvFileWriterOptions;
}