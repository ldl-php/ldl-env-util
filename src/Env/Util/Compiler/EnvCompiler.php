<?php declare(strict_types=1);

namespace LDL\Env\Util\Compiler;

use LDL\Env\Util\Compiler\Collection\EnvCompilerCollection;
use LDL\Env\Util\Line\Collection\EnvLineCollection;
use LDL\Env\Util\Line\Collection\EnvLineCollectionInterface;
use LDL\Env\Util\Line\Type\Directive\EnvCompilerDirectiveInterface;
use LDL\Env\Util\Line\Type\EnvUnknownLine;
use LDL\Framework\Helper\IterableHelper;
use LDL\Type\Collection\Types\String\StringCollection;

class EnvCompiler
{
    /**
     * @var array
     */
    private $compilers;

    /**
     * @var EnvCompilerDirectiveInterface|null
     */
    private $startDirective;

    public function __construct(
        EnvCompilerCollection $compilers=null,
        EnvCompilerDirectiveInterface $startDirective=null
    )
    {
        $this->compilers = $compilers ?? new EnvCompilerCollection();
        $this->startDirective = $startDirective;
    }

    /**
     * {@inheritdoc}
     */
    public function compile(EnvLineCollectionInterface $lines) : StringCollection
    {
        $curLines = new EnvLineCollection();
        $curDirective = $this->startDirective;

        foreach($lines as $line){
            $isDirective = $line instanceof EnvCompilerDirectiveInterface;

            /**
             * START directive
             */
            if($isDirective && $line->isStart()){
                $curDirective = $line;
                continue;
            }

            /**
             * STOP Directive, go back to original start directive
             */
            if($isDirective && false === $line->isStart()){
                $curDirective = $this->startDirective;
                continue;
            }

            $line = $curDirective ? $this->compilers->compile($line, $lines, $curLines, $curDirective) : $line;

            if(null === $line || $line instanceof EnvUnknownLine){
                continue;
            }

            $curLines->append($line);
        }

        return new StringCollection(IterableHelper::map($curLines, static function($v){
            return (string)$v;
        }));

    }
}
