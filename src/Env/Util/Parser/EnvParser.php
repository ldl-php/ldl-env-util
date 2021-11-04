<?php declare(strict_types=1);

namespace LDL\Env\Util\Parser;

use LDL\Env\Util\Line\Type\EnvUnknownLine;
use LDL\Env\Util\Line\Collection\EnvLineCollection;
use LDL\Env\Util\Line\Collection\EnvLineCollectionInterface;
use LDL\Env\Util\Line\Parser\Comment\EnvLineCommentParser;
use LDL\Env\Util\Line\Parser\Directive\EnvLineCompilerDirectiveParser;
use LDL\Env\Util\Line\Parser\EmptyLine\EnvEmptyLineParser;
use LDL\Env\Util\Line\Parser\EnvLineParserCollection;
use LDL\Env\Util\Line\Parser\Variable\EnvLineVarParser;
use LDL\Framework\Base\Constants;
use LDL\Framework\Helper\IterableHelper;

class EnvParser implements EnvParserInterface
{
    public function parse(
        iterable $lines,
        EnvLineParserCollection $parsers=null
    ) : EnvLineCollectionInterface
    {
        /**
         * If no parsers are passed, create a default collection of parsers
         */
        if(null === $parsers) {
            $parsers = new EnvLineParserCollection([
                new EnvLineCommentParser(),
                new EnvLineCompilerDirectiveParser(),
                new EnvEmptyLineParser(),
                new EnvLineVarParser()
            ]);
        }

        $lines = IterableHelper::filterByValueType($lines, Constants::PHP_TYPE_STRING);

        $return = new EnvLineCollection();

        foreach($lines as $line){
            $l = $parsers->parse($line);
            $return->append(null ===  $l ? new EnvUnknownLine($line) : $l);
        }

        return $return;
    }
}