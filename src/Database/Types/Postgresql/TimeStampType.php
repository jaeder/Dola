<?php

namespace DFZ\Dola\Database\Types\Postgresql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use DFZ\Dola\Database\Types\Type;

class TimeStampType extends Type
{
    const NAME = 'timestamp';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'timestamp(0) without time zone';
    }
}
