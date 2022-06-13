<?php

declare(strict_types=1);

namespace LDL\Env\Util;

class EnvUtil
{
    public static function set(string $var, string $value): void
    {
        putenv("$var=$value");
        $_ENV[$var] = $value;
    }

    public static function unset(string $var): void
    {
        if (array_key_exists($var, $_ENV)) {
            unset($_ENV[$var]);
        }

        putenv($var);
    }

    public static function get(string $var): ?string
    {
        $getEnv = getenv($var);

        if (false !== $getEnv) {
            return $getEnv;
        }

        if (array_key_exists($var, $_ENV)) {
            return (string) $_ENV[$var];
        }

        return null;
    }
}
