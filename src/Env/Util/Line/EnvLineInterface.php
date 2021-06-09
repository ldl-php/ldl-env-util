<?php declare(strict_types=1);

namespace LDL\Env\Util\Line;

interface EnvLineInterface
{
    public function getString() : string;

    public function __toString() : string;
}