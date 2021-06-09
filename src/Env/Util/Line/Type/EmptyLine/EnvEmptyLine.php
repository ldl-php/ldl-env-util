<?php declare(strict_types=1);

namespace LDL\Env\Util\Line\Type\EmptyLine;

use LDL\Env\Util\Line\Type\AbstractEnvLine;

class EnvEmptyLine extends AbstractEnvLine implements EnvEmptyLineInterface
{
    public function __construct()
    {
        parent::__construct('');
    }
}