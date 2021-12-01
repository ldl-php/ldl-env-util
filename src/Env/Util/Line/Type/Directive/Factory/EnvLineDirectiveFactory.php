<?php declare(strict_types=1);

namespace LDL\Env\Util\Line\Type\Directive\Factory;

use LDL\Env\Util\Compiler\Collection\EnvCompilerDirectiveCollection;
use LDL\Env\Util\Compiler\Collection\EnvCompilerDirectiveCollectionInterface;
use LDL\Env\Util\Compiler\EnvCompilerDirectiveInterface;
use LDL\Env\Util\Line\Type\Directive\EnvLineDirective;
use LDL\Env\Util\Line\Type\Directive\EnvLineDirectiveInterface;

final class EnvLineDirectiveFactory implements EnvLineDirectiveFactoryInterface
{

    public static function create(bool $isStart, ?iterable $directives) : EnvLineDirectiveInterface
    {
        $directives = new EnvCompilerDirectiveCollection($directives);
        $return = [];

        /**
         * @var EnvCompilerDirectiveInterface $directive
         */
        foreach($directives as $directive){
            foreach($directive->toArray() as $key=>$value){
                $return[$key] = $value;
            }
        }

        $line = sprintf(
            '%s %s=%s',
            EnvLineDirectiveInterface::ENV_COMPILER_STRING,
            EnvLineDirectiveInterface::ENV_COMPILER_START,
            json_encode($return, \JSON_THROW_ON_ERROR)
        );

        return new EnvLineDirective($line,true, $return);
    }

    public static function getDirectives(
        EnvLineDirectiveInterface $line,
        iterable $directives=null
    ) : EnvCompilerDirectiveCollectionInterface
    {
        $matching = [];
        $directives = new EnvCompilerDirectiveCollection($directives);

        /**
         * @var EnvCompilerDirectiveInterface $directive
         */
        foreach($directives as $directive){
            if(null !== $directive::fromOptions($line->getCompilerOptions())){
                $matching[] = $directive;
            }
        }

        return new EnvCompilerDirectiveCollection($matching);
    }

}