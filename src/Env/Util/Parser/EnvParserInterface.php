<?php declare(strict_types=1);

/**
 * The env parser is in charge of taking in a collection of lines and detecting which kind of line are we dealing with.
 */

namespace LDL\Env\Util\Parser;

use LDL\Env\Util\Line\Collection\EnvLineCollectionInterface;

interface EnvParserInterface {

    /**
     * @param iterable $lines
     * @return EnvLineCollectionInterface
     */
    public function parse(iterable $lines) : EnvLineCollectionInterface;

}
