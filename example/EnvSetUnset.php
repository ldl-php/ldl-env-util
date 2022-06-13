<?php

declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use LDL\Env\Util\EnvUtil;

echo "Set ENV variable TEST=1\n";

EnvUtil::set('TEST', '1');
dump(EnvUtil::get('TEST'));

echo "Unset ENV variable TEST\n";
EnvUtil::unset('TEST');
dump(EnvUtil::get('TEST'));
