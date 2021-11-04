<?php declare(strict_types=1);

namespace LDL\Env\Util\Line\Parser\Directive;

use LDL\Env\Util\Line\EnvLineInterface;
use LDL\Env\Util\Line\Type\Directive\EnvCompilerDirective;
use LDL\Env\Util\Line\Type\Directive\EnvCompilerDirectiveInterface;

class EnvLineCompilerDirectiveParser implements EnvLineCompilerDirectiveParserInterface
{
    public function createFromString(string $line) : ?EnvLineInterface
    {
        $dLength = strlen(EnvCompilerDirectiveInterface::ENV_COMPILER_STRING);
        $directive = trim((string)substr($line,0, $dLength));

        if(EnvCompilerDirectiveInterface::ENV_COMPILER_STRING !== $directive){
            return null;
        }

        $options = substr($line, $dLength+1);

        $isStart = strpos($options, EnvCompilerDirectiveInterface::ENV_COMPILER_START) === 0;

        if(!$isStart){
            return new EnvCompilerDirective($line, false);
        }

        try {
            $options = substr($options, strpos($options, '=') + 1);
            $options = json_decode($options, true, 512, \JSON_THROW_ON_ERROR);

            return new EnvCompilerDirective(
                $line,
                $isStart,
                $options
            );
        }catch(\Exception $e){
            return null;
        }

    }

    public function transform(EnvLineInterface $line): string
    {
        return '';
    }
}