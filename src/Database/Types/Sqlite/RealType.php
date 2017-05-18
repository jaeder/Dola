<?php

namespace DFZ\Dola\Database\Types\Sqlite;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use DFZ\Dola\Database\Types\Type;

class RealType extends Type
{
    const NAME = 'real';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'real';
    }
}
