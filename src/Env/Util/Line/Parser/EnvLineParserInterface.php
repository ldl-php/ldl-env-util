<?php declare(strict_types=1);

namespace LDL\Env\Util\Line\Parser;

use LDL\Env\Util\Line\EnvLineInterface;

interface EnvLineParserInterface
{
    public function createFromString(string $line): ?EnvLineInterface;
}