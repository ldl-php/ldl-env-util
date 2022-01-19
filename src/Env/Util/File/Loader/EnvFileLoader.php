<?php

declare(strict_types=1);

namespace LDL\Env\Util\File\Loader;

use LDL\Env\Util\Compiler\EnvCompiler;
use LDL\Env\Util\Compiler\EnvCompilerInterface;
use LDL\Env\Util\File\Parser\EnvFileParser;
use LDL\Env\Util\File\Parser\EnvFileParserInterface;
use LDL\Env\Util\Line\Type\Variable\EnvLineVarInterface;
use LDL\Env\Util\Loader\EnvLoader;

class EnvFileLoader
{
    /**
     * @var EnvFileParserInterface
     */
    private $parser;

    /**
     * @var EnvCompilerInterface
     */
    private $compiler;

    public function __construct(
        EnvFileParserInterface $parser = null,
        EnvCompilerInterface $compiler = null
    ) {
        $this->parser = $parser ?? new EnvFileParser(null, false);
        $this->compiler = $compiler ?? new EnvCompiler();
    }

    public function load(string $file, bool $setENV = true): void
    {
        EnvLoader::load($this->compiler->compile($this->parser->parse([$file]))->filter(static function ($l) {
            return $l instanceof EnvLineVarInterface;
        }));
    }
}
