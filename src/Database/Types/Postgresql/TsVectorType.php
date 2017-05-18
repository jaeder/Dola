<?php

namespace DFZ\Dola\Database\Types\Postgresql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use DFZ\Dola\Database\Types\Type;

class TsVectorType extends Type
{
    const NAME = 'tsvector';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'tsvector';
    }
}
