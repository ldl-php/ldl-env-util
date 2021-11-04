<?php declare(strict_types=1);

namespace LDL\Env\Util\Line\Parser\Variable;

use LDL\Env\Util\Line\EnvLineInterface;
use LDL\Env\Util\Line\Type\Variable\EnvLineVar;
use LDL\Env\Util\Line\Type\Variable\EnvLineVarInterface;
use Symfony\Component\String\UnicodeString;

class EnvLineVarParser implements EnvLineVarParserInterface
{

    /**
     * @param string $line
     * @param string|null $prefix
     * @return EnvLineVarInterface
     */
    public function createFromString(string $line, ?string $prefix = '') : ?EnvLineInterface
    {
        $line = new UnicodeString($line);
        $isVariable = $line->match('#^[\s?\w]+=.*$#');

        if(count($isVariable) === 0){
            return null;
        }

        $equalsPosition= $line->indexOf('=');

        $value = $line->slice($equalsPosition+1)->trim("\r\n")->toString();

        return new EnvLineVar(
            $line->toString(),
            $line->slice(0, $equalsPosition)->trim()->toString(),
            /**
             * Convert \r\n string literals into non-escaped sequences
             */
            preg_replace_callback(
                '#\\\\([nrtvf\\\\$"]|[0-7]{1,3}|\x[0-9A-Fa-f]{1,2})#',
                static function($value){
                    return stripcslashes($value[0]);
                }, $value
            ),
            (string)$prefix
        );
    }
}