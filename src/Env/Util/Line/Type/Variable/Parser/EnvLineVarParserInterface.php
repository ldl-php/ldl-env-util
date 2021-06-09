<?php declare(strict_types=1);

namespace LDL\Env\Util\Line\Type\Variable\Parser;

use LDL\Env\Util\Line\EnvLineInterface;
use LDL\Env\Util\Line\Parser\EnvLineParserInterface;
use LDL\Env\Util\Line\Type\Variable\EnvLineVarInterface;

interface EnvLineVarParserInterface extends EnvLineParserInterface
{
    /**
     * @param string $line
     * @param string $prefix
     *
     * @return EnvLineVarInterface|null
     */
    public function createFromString(string $line, ?string $prefix = ''): ?EnvLineInterface;
}