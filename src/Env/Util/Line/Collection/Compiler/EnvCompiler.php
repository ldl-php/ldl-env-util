<?php declare(strict_types=1);

namespace LDL\Env\Util\Line\Collection\Compiler;

use LDL\Env\Util\Line\Collection\EnvLineCollection;
use LDL\Env\Util\Line\Collection\EnvLineCollectionInterface;
use LDL\Env\Util\Line\EnvLineInterface;
use LDL\Env\Util\Line\Type\Comment\EnvLineComment;
use LDL\Env\Util\Line\Type\Comment\EnvLineCommentInterface;
use LDL\Env\Util\Line\Type\Directive\EnvLineDirectiveInterface;
use LDL\Env\Util\Line\Type\EmptyLine\EnvEmptyLine;
use LDL\Env\Util\Line\Type\Variable\EnvLineVarInterface;
use LDL\Env\Util\Line\Type\Variable\Parser\EnvLineVarParser;

class EnvCompiler implements EnvCompilerInterface
{
    /**
     * @var Options\EnvCompilerOptions
     */
    private $options;

    public function __construct(Options\EnvCompilerOptionsInterface $options = null)
    {
        $this->options = $options ?? Options\EnvCompilerOptions::fromArray([]);
    }

    /**
     * {@inheritdoc}
     */
    public function compile(EnvLineCollectionInterface $lines) : EnvLineCollectionInterface
    {
        $return = new EnvLineCollection();
        $options = $this->options;

        /**
         * @var EnvLineInterface $line
         */
        foreach($lines as $line){
            $isDirective = $line instanceof EnvLineDirectiveInterface;

            if($isDirective){
                $compilerOptions = $line->getCompilerOptions();

                if(null !== $compilerOptions){
                    $options = $compilerOptions->merge($options);
                }

                $line = new EnvLineComment(sprintf('#%s', $line->getString()));
            }

            if($line instanceof EnvLineCommentInterface && false === $options->isCommentsEnabled()){
                continue;
            }

            if($line instanceof EnvLineVarInterface && true === $options->isAddPrefix()) {
                $line = (new EnvLineVarParser())->createFromString(
                    $line->getString(),
                    $line->getPrefix()
                );

                if($options->isVarNameToUpperCase()){
                    $line = $line->toUpperCase();
                }
            }

            $return->append($line);

            /**
             * Add carriage return after directive
             */
            if($isDirective){
                $return->append(new EnvEmptyLine());
            }
        }

        return $return;
    }

    public function getOptions(): Options\EnvCompilerOptions
    {
        return $this->options;
    }
}
