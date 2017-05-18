<?php

namespace DFZ\Dola\Database\Types\Common;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use DFZ\Dola\Database\Types\Type;

class JsonType extends Type
{
    const NAME = 'json';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'json';
    }
}
