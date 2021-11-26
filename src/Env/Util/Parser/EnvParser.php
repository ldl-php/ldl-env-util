<?php declare(strict_types=1);

namespace LDL\Env\Util\Parser;

use LDL\Env\Util\Line\Parser\EnvLineParserCollectionInterface;
use LDL\Env\Util\Line\Type\EnvUnknownLine;
use LDL\Env\Util\Line\Collection\EnvLineCollection;
use LDL\Env\Util\Line\Collection\EnvLineCollectionInterface;
use LDL\Env\Util\Line\Parser\EnvLineParserCollection;
use LDL\Framework\Base\Collection\CallableCollectionInterface;
use LDL\Framework\Base\Constants;
use LDL\Framework\Helper\IterableHelper;

class EnvParser implements EnvParserInterface
{

    /**
     * @var EnvLineParserCollectionInterface
     */
    private $parsers;

    /**
     * @var CallableCollectionInterface
     */
    private $beforeParse;

    /**
     * @var CallableCollectionInterface
     */
    private $afterParse;

    public function __construct(
        EnvLineParserCollectionInterface $parsers=null,
        CallableCollectionInterface $beforeParse=null,
        CallableCollectionInterface $afterParse=null
    )
    {
        /**
         * If no parsers are passed, create a default collection of parsers
         */
        $this->parsers = $parsers ?? new EnvLineParserCollection();
        $this->beforeParse = $beforeParse;
        $this->afterParse = $afterParse;
    }

    public function parse(iterable $lines) : EnvLineCollectionInterface
    {
        $lines = IterableHelper::filterByValueType($lines, Constants::PHP_TYPE_STRING);

        $return = new EnvLineCollection();

        foreach($lines as $line){
            if(null !==$this->beforeParse){
                $this->beforeParse->call($line, $lines, $this->parsers, $this);
            }

            $l = $this->parsers->parse($line) ?? new EnvUnknownLine($line);

            if(null !== $this->afterParse){
                $this->afterParse->call($l, $line, $lines, $this->parsers, $this);
            }

            $return->append($l);
        }

        return $return;
    }
}