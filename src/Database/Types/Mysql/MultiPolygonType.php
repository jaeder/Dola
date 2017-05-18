<?php

namespace DFZ\Dola\Database\Types\Mysql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use DFZ\Dola\Database\Types\Type;

class MultiPolygonType extends Type
{
    const NAME = 'multipolygon';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'multipolygon';
    }
}
