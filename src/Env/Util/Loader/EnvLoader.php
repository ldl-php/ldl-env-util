<?php

declare(strict_types=1);

namespace LDL\Env\Util\Loader;

use LDL\Env\Util\EnvUtil;
use LDL\Env\Util\File\Exception\ReadEnvFileException;
use LDL\Env\Util\File\Parser\EnvFileParser;
use LDL\Env\Util\Line\Collection\EnvLineCollectionInterface;
use LDL\Env\Util\Line\Type\Variable\EnvLineVarInterface;
use LDL\Type\Collection\Interfaces\Type\StringCollectionInterface;
use LDL\Type\Collection\Types\String\StringCollection;

final class EnvLoader
{
    /**
     * @var string[]
     */
    private static $loaded = [];

    public static function load(EnvLineCollectionInterface $lines): void
    {
        foreach ($lines as $line) {
            if ($line instanceof EnvLineVarInterface) {
                EnvUtil::set($line->getVar(true), $line->getValue());
            }
        }
    }

    /**
     * Loads a .env file which is already compiled (in the form of VAR=VALUE)
     * NOTE: This method must ONLY be used with a regular .env file which has already been compiled.
     *
     * @throws ReadEnvFileException
     */
    public static function loadFile(string $file, bool $cache = true): void
    {
        /*
         * If the file was previously loaded and cache is enabled, do not load the file again.
         */
        if ($cache && array_key_exists($file, self::$loaded)) {
            return;
        }

        try {
            self::load(
                (new EnvFileParser(null, false))
                    ->parse([$file], false)
            );

            self::$loaded[$file] = true;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    /**
     * Returns a string collection of file paths loaded by self::loadFile.
     */
    public static function getLoadedFiles(): StringCollectionInterface
    {
        return new StringCollection(self::$loaded);
    }
}
