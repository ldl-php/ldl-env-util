<?php declare(strict_types=1);

/**
 * The file parser takes in a collection of readable files, reads lines from each one of them
 * and parses the obtained lines from said files.
 */

namespace LDL\Env\Util\File\Parser;

use LDL\Env\Util\Line\Collection\EnvLineCollection;
use LDL\Env\Util\Line\Collection\EnvLineCollectionInterface;
use LDL\Env\Util\Line\Parser\Variable\EnvLineVarParser;
use LDL\Env\Util\Line\Type\Comment\EnvLineComment;
use LDL\Env\Util\Line\Type\Directive\EnvCompilerDirective;
use LDL\Env\Util\Line\Type\Directive\EnvCompilerDirectiveInterface;
use LDL\Env\Util\Line\Type\Variable\EnvLineVarInterface;
use LDL\Env\Util\Parser\EnvParserInterface;
use LDL\Env\Util\File\Exception\ReadEnvFileException;
use LDL\Env\Util\Parser\EnvParser;
use LDL\File\Collection\ReadableFileCollection;
use LDL\Env\Util\Line\Type\EmptyLine\EnvEmptyLine;
use LDL\File\Contracts\FileInterface;
use LDL\Framework\Base\Collection\Contracts\AppendInPositionInterface;
use LDL\Framework\Helper\IterableHelper;

class EnvFileParser implements EnvFileParserInterface
{
    /**
     * @var Options\EnvFileParserOptionsInterface
     */
    private $options;

    /**
     * @var EnvParserInterface
     */
    private $parser;

    public function __construct(
        EnvParserInterface $parser=null,
        Options\EnvFileParserOptionsInterface $options=null
    )
    {
        $this->parser = $parser ?? new EnvParser();
        $this->options = $options ?? new Options\EnvFileParserOptions();
    }

    /**
     * {@inheritdoc}
     */
    public function parse(iterable $files) : EnvLineCollectionInterface
    {
        if(!$files instanceof ReadableFileCollection) {
            $files = new ReadableFileCollection($files);
        }

        $return = new EnvLineCollection();

        $options = $this->options;

        /**
         * Parse each env file
         * @var FileInterface $file
         */
        foreach($files as $file) {

            try{
                $currentFileLines = new EnvLineCollection();

                $currentFileLines->appendMany(
                    $this->prefixVarsWithDirectory(
                        $this->parser->parse($file->getLines()),
                        $file
                    )
                );

                $fileComment = new EnvLineComment(sprintf(
                    '#Taken from %s', $file
                ));

                $currentFileLines->appendInPosition(
                    $fileComment,
                    $currentFileLines->getFirst() instanceof EnvCompilerDirectiveInterface ? 2 : 1
                );

                $return->appendMany($currentFileLines);

            }catch(\Exception $e){
                if($options->isSkipUnreadable()){
                    continue;
                }

                throw new ReadEnvFileException(sprintf(
                    'File: "%s" is not readable',
                    $file->getPath()
                ));
            }
        }

        return $return;
    }

    public function getOptions() : Options\EnvFileParserOptionsInterface
    {
        return $this->options;
    }

    //<editor-fold desc="Private methods">
    private function prefixVarsWithDirectory(
        EnvLineCollectionInterface $lines,
        FileInterface $file
    ) : EnvLineCollectionInterface
    {
        $depth = $this->options->getDirPrefixDepth();

        if(0 === $depth){
            return $lines;
        }


        $prefix = explode(\DIRECTORY_SEPARATOR, (string)$file->getDirectory());
        $prefix[] = '';
        $prefix = implode('_', array_slice($prefix, count($prefix) - ($depth+1)));

        return new EnvLineCollection(
            IterableHelper::map($lines, static function($line) use ($prefix){
                if (!$line instanceof EnvLineVarInterface) {
                    return $line;
                }

                return (new EnvLineVarParser())->createFromString(
                    $line->getString(),
                    $prefix
                );
            }, true)
        );
    }
    //</editor-fold>
}