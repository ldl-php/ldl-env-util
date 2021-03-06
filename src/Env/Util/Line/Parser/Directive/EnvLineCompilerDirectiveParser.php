<?php declare(strict_types=1);

namespace LDL\Env\Util\Line\Parser\Directive;

use LDL\Env\Util\Line\EnvLineInterface;
use LDL\Env\Util\Line\Type\Directive\EnvLineDirective;
use LDL\Env\Util\Line\Type\Directive\EnvLineDirectiveInterface;

class EnvLineCompilerDirectiveParser implements EnvLineCompilerDirectiveParserInterface
{

    /**
     * @param string $line
     * @return EnvLineDirectiveInterface|null
     */
    public function createFromString(string $line) : ?EnvLineInterface
    {
        $dLength = strlen(EnvLineDirectiveInterface::ENV_COMPILER_STRING);
        $directive = trim((string)substr($line,0, $dLength));

        if(EnvLineDirectiveInterface::ENV_COMPILER_STRING !== $directive){
            return null;
        }

        $options = substr($line, $dLength+1);

        $isStart = strpos($options, EnvLineDirectiveInterface::ENV_COMPILER_START) === 0;

        if(!$isStart){
            return new EnvLineDirective($line, false);
        }

        try {
            $options = substr($options, strpos($options, '=') + 1);
            $options = json_decode($options, true, 512, \JSON_THROW_ON_ERROR);

            return new EnvLineDirective(
                $line,
                $isStart,
                $options
            );
        }catch(\Exception $e){
            return null;
        }

    }
}