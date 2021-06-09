<?php declare(strict_types=1);

namespace LDL\Env\Util\Line\Parser;

use LDL\Env\Util\Line\EnvLineInterface;

interface EnvLineParserCollectionInterface
{
    public function parse(string $line): ?EnvLineInterface;
}