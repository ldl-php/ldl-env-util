<?php declare(strict_types=1);
/**
 * Only in charge of writing the final .env-compiled file
 */
namespace LDL\Env\Util\File\Writer;

use LDL\Env\Util\Line\Collection\EnvLineCollectionInterface;

class EnvFileWriter implements EnvFileWriterInterface
{
    /**
     * @var Options\EnvFileWriterOptions
     */
    private $options;

    public function __construct(Options\EnvFileWriterOptionsInterface $options = null)
    {
        $this->options = $options ?? Options\EnvFileWriterOptions::fromArray([]);
    }

    /**
     * {@inheritdoc}
     */
    public function write(
        EnvLineCollectionInterface $envData,
        string $file
    ): void
    {
        $options = $this->options;

        if(false === $options->isForce() && true === file_exists($options->getFilename())){
            $msg = sprintf(
                'File: %s already exists!. Force it to overwrite',
                $options->getFilename()
            );

            throw new Exception\FileAlreadyExistsException($msg);
        }

        if(file_exists($file) && !is_writable($file)){
            throw new Exception\PermissionException("Could not write to file \"$file\", please check file permissions");
        }

        file_put_contents($file, $envData);
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(): Options\EnvFileWriterOptions
    {
        return clone($this->options);
    }
}