<?php declare(strict_types=1);

namespace LDL\Env\Util\Line\Type\Directive;

use LDL\Env\Util\Line\Collection\Compiler\Options\EnvCompilerOptions;
use LDL\Env\Util\Line\Collection\Compiler\Options\EnvCompilerOptionsInterface;
use LDL\Env\Util\File\Parser\Options\EnvFileParserOptions;
use LDL\Env\Util\Line\Exception\EnvLineTypeException;
use LDL\Env\Util\Line\Type\AbstractEnvLine;
use LDL\Env\Util\File\Parser\Options\EnvFileParserOptionsInterface;

class EnvDirective extends AbstractEnvLine implements EnvLineDirectiveInterface
{
    /**
     * @var EnvCompilerOptionsInterface|null
     */
    private $compilerOptions;

    /**
     * @var EnvFileParserOptionsInterface|null
     */
    private $parserOptions;

    public function __construct(
        string $string,
        ?EnvCompilerOptionsInterface $compilerOptions = null,
        ?EnvFileParserOptionsInterface $parserOptions = null
    )
    {
        parent::__construct($string);
        $this->compilerOptions = $compilerOptions;
        $this->parserOptions = $parserOptions;
    }

    public function getCompilerOptions() : ?EnvCompilerOptionsInterface
    {
        return $this->compilerOptions;
    }

    public function getParserOptions() : ?EnvFileParserOptionsInterface
    {
        return $this->parserOptions;
    }

    public function parse(array $matches): void
    {
        $parse = [];

        foreach($matches as $operator){
            $key = substr($operator, 0, strpos($operator,'='));
            $value = substr($operator, strlen($key) + 1);

            if(array_key_exists($key, $parse)){
                $msg = sprintf('Duplicate %s options', $key);
                throw new EnvLineTypeException($msg);
            }

            $parse[$key] = json_decode($value, true, 512, \JSON_THROW_ON_ERROR);

            switch(strtoupper($key)){
                case 'COMPILER':
                    $this->compilerOptions = EnvCompilerOptions::fromArray($parse[$key]);
                    break;
                case 'PARSER':
                    $this->parserOptions = EnvFileParserOptions::fromArray($parse[$key]);
                    break;
            }
        }
    }

}