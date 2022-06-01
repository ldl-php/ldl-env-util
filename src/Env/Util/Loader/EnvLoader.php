<?php

declare(strict_types=1);

namespace LDL\Env\Util\Loader;

use LDL\Env\Util\File\Exception\ReadEnvFileException;
use LDL\Env\Util\File\Parser\EnvFileParser;
use LDL\Env\Util\Line\Collection\EnvLineCollectionInterface;
use LDL\Env\Util\Line\Type\Variable\EnvLineVarInterface;
use LDL\Framework\Helper\IterableHelper;

final class EnvLoader
{
    public static function load(EnvLineCollectionInterface $lines): void
    {
        $lines = IterableHelper::filter($lines, static function ($l) {
            return $l instanceof EnvLineVarInterface;
        });

        IterableHelper::map($lines, static function (EnvLineVarInterface $l) {
            putenv((string) $l);
            $_ENV[$l->getVar(true)] = $l->getValue();
        });
    }

    /**
     * This method must be used with an .env file which has already been compiled.
     *
     * @throws ReadEnvFileException
     */
    public static function loadFile(string $file): void
    {
        self::load(
            (new EnvFileParser(null, false))
                ->parse([$file], false)
        );
    }
}
