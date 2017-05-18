<?php

namespace DFZ\Dola\Database\Types\Postgresql;

use DFZ\Dola\Database\Types\Common\DoubleType;

class DoublePrecisionType extends DoubleType
{
    const NAME = 'double precision';
    const DBTYPE = 'float8';
}
