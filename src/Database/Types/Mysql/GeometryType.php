<?php

namespace DFZ\Dola\Database\Types\Mysql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use DFZ\Dola\Database\Types\Type;

class GeometryType extends Type
{
    const NAME = 'geometry';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'geometry';
    }
}
