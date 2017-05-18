<?php

namespace DFZ\Dola\Database\Types\Mysql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use DFZ\Dola\Database\Types\Type;

class LongTextType extends Type
{
    const NAME = 'longtext';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'longtext';
    }
}
