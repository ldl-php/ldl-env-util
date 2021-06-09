<?php declare(strict_types=1);

namespace LDL\Env\Util\File\Parser;

use LDL\Env\Util\Line\Collection\Compiler\EnvCompiler;
use LDL\Env\Util\Line\Collection\Compiler\EnvCompilerInterface;
use LDL\Env\Util\Line\Collection\EnvLineCollection;
use LDL\Env\Util\Line\Collection\EnvLineCollectionInterface;
use LDL\Env\Util\Line\Parser\EnvLineParserCollection;
use LDL\Env\Util\Line\Parser\EnvLineParserCollectionInterface;
use LDL\Env\Util\Line\Type\Comment\EnvLineComment;
use LDL\Env\Util\Line\Type\Comment\Parser\EnvLineCommentParser;
use LDL\Env\Util\Line\Type\Directive\EnvLineDirectiveInterface;
use LDL\Env\Util\Line\Type\Directive\Parser\EnvLineDirectiveParser;
use LDL\Env\Util\Line\Type\EmptyLine\EnvEmptyLine;
use LDL\Env\Util\Line\Type\EmptyLine\Parser\EnvEmptyLineParser;
use LDL\Env\Util\Line\Type\Variable\EnvLineVarInterface;
use LDL\Env\Util\Line\Type\Variable\Parser\EnvLineVarParser;
use LDL\File\Collection\ReadableFileCollection;

class EnvFileParser implements EnvFileParserInterface
{
    /**
     * @var Options\EnvFileParserOptionsInterface
     */
    private $options;

    /**
     * @var EnvCompilerInterface
     */
    private $compiler;

    /**
     * @var EnvLineParserCollectionInterface
     */
    private $parsers;

    public function __construct(
        Options\EnvFileParserOptionsInterface $options=null,
        EnvCompilerInterface $compiler=null,
        EnvLineParserCollectionInterface $parsers=null
    )
    {
        $this->options = $options ?? new Options\EnvFileParserOptions();
        $this->compiler = $compiler ?? new EnvCompiler();

        /**
         * If no parsers collection is passed, create one by default
         */
        if(null === $parsers){
            $parsers = new EnvLineParserCollection([
                new EnvLineCommentParser(),
                new EnvLineDirectiveParser(),
                new EnvEmptyLineParser(),
                new EnvLineVarParser()
            ]);
        }

        $this->parsers = $parsers;
    }

    /**
     * {@inheritdoc}
     */
    public function parse(
        ReadableFileCollection $files,
        Options\EnvFileParserOptionsInterface $options=null
    ) : EnvLineCollectionInterface
    {
        $return = new EnvLineCollection();

        foreach($files as $foundFile) {
            $file = new \SplFileInfo($foundFile);
            $options = $this->options;

            if(false === $file->isReadable()){
                if($options->isSkipUnreadable()){
                    continue;
                }

                throw new Exception\PermissionException(sprintf(
                    'File: "%s" is not readable',
                    $file->getPathname()
                ));
            }

            $linesFromCurrentFile = new EnvLineCollection();

            /**
             * Add from which file the variables come from
             */
            $linesFromCurrentFile->appendMany([
                new EnvEmptyLine(),
                new EnvLineComment(sprintf('#Taken from: %s', $file->getRealPath())),
                new EnvEmptyLine()
            ]);

            $fp = fopen($file->getRealPath(), 'rb');

            $fileLine = 0;

            while ($line = fgets($fp)) {
                $fileLine++;

                $line = $this->parsers->parse($line);

                if($line instanceof EnvLineDirectiveInterface){
                    $parserOptions = $line->getParserOptions();

                    if(null !== $parserOptions){
                        $options = $parserOptions->merge($options);
                    }
                }

                if(true === $options->isIgnore()){
                    /**
                     * Add which file was ignored
                     */
                    $linesFromCurrentFile->appendMany([
                        $line,
                        new EnvLineComment(
                            sprintf(
                                '#IGNORED LINES FROM LINE %s ONWARDS (see parser options above)',
                                $fileLine
                            )
                        )
                    ]);

                    break;
                }

                if(null === $line){
                    if($options->isIgnoreSyntaxErrors()) {
                        continue;
                    }

                    throw new Exception\SyntaxErrorException(
                        sprintf(
                            'Syntax error in file: "%s", line: %s',
                            $file,
                            $fileLine
                        )
                    );
                }

                if($line instanceof EnvLineVarInterface && $options->getDirPrefixDepth() > 0){
                    $prefix = explode(\DIRECTORY_SEPARATOR, $file->getPath());
                    $prefix[] = '';

                    $line = (new EnvLineVarParser())->createFromString(
                        $line->getString(),
                        implode(
                            '_',
                            array_slice($prefix,count($prefix) - ($options->getDirPrefixDepth()+1))
                        )
                    );
                }

                $linesFromCurrentFile->append($line);
            }

            fclose($fp);

            $return->appendMany(\iterator_to_array($this->compiler->compile($linesFromCurrentFile)));
        }

        return $return;
    }

    public function getOptions() : Options\EnvFileParserOptionsInterface
    {
        return $this->options;
    }
}