<?php

namespace DFZ\Dola\Database\Types\Common;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use DFZ\Dola\Database\Types\Type;

class TextType extends Type
{
    const NAME = 'text';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'text';
    }
}
