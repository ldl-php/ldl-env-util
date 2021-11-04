<?php declare(strict_types=1);

namespace LDL\Env\Util\Line\Parser\Comment;

use LDL\Env\Util\Line\EnvLineInterface;
use LDL\Env\Util\Line\Type\Comment\EnvLineComment;
use Symfony\Component\String\UnicodeString;

class EnvLineCommentParser implements EnvLineCommentParserInterface
{
    public function createFromString(string $line) : ?EnvLineInterface
    {
        $str = (new UnicodeString($line))->trim();

        if(false === $str->startsWith('#')){
            return null;
        }

        return new EnvLineComment($str->toString());
    }
}