<?php

declare(strict_types=1);

/**
 * The file parser takes in a collection of readable files, reads lines from each one of them
 * and parses the obtained lines from said files.
 */

namespace LDL\Env\Util\File\Parser;

use LDL\Env\Util\File\Exception\ReadEnvFileException;
use LDL\Env\Util\Line\Collection\EnvLineCollection;
use LDL\Env\Util\Line\Collection\EnvLineCollectionInterface;
use LDL\Env\Util\Line\Type\Comment\EnvLineComment;
use LDL\Env\Util\Line\Type\Directive\EnvLineDirectiveInterface;
use LDL\Env\Util\Parser\EnvParser;
use LDL\Env\Util\Parser\EnvParserInterface;
use LDL\File\Collection\ReadableFileCollection;
use LDL\File\Contracts\FileInterface;
use LDL\Framework\Base\Collection\CallableCollectionInterface;

class EnvFileParser implements EnvFileParserInterface
{
    /**
     * @var EnvParserInterface
     */
    private $parser;

    /**
     * @var bool
     */
    private $skipUnreadable;

    /**
     * @var CallableCollectionInterface
     */
    private $beforeParse;

    /**
     * @var CallableCollectionInterface
     */
    private $afterParse;

    public function __construct(
        ?EnvParserInterface $parser,
        ?bool $skipUnreadable,
        CallableCollectionInterface $onBeforeParse = null,
        CallableCollectionInterface $onAfterParse = null
    ) {
        $this->parser = $parser ?? new EnvParser();
        $this->skipUnreadable = $skipUnreadable ?? false;
        $this->beforeParse = $onBeforeParse;
        $this->afterParse = $onAfterParse;
    }

    /**
     * {@inheritdoc}
     */
    public function parse(iterable $files, bool $prefixDirectory = true): EnvLineCollectionInterface
    {
        if (!$files instanceof ReadableFileCollection) {
            $files = new ReadableFileCollection($files);
        }

        $return = new EnvLineCollection();

        /**
         * Parse each env file.
         *
         * @var FileInterface $file
         */
        foreach ($files as $file) {
            if (null !== $this->beforeParse) {
                $this->beforeParse->call($file, $files, $return, $this);
            }

            try {
                $currentFileLines = new EnvLineCollection();

                $currentFileLines->appendMany(
                    $this->parser->parse(
                            $file->getLines(),
                            $prefixDirectory ? dirname($file->getPath()) : null,
                            $prefixDirectory ? \DIRECTORY_SEPARATOR : null
                        )
                );

                $fileComment = new EnvLineComment(sprintf(
                    '#Taken from %s', $file
                ));

                $currentFileLines->appendInPosition(
                    $fileComment,
                    $currentFileLines->getFirst() instanceof EnvLineDirectiveInterface ? 2 : 1
                );

                $return->appendMany($currentFileLines);

                if (null !== $this->afterParse) {
                    $this->afterParse->call($file, $currentFileLines, $files, $return, $this);
                }
            } catch (\Exception $e) {
                if ($this->skipUnreadable) {
                    continue;
                }

                throw new ReadEnvFileException(sprintf('File: "%s" is not readable', $file->getPath()));
            }
        }

        return $return;
    }
}
