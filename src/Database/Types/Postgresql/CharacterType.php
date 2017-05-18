<?php

namespace DFZ\Dola\Database\Types\Postgresql;

use DFZ\Dola\Database\Types\Common\CharType;

class CharacterType extends CharType
{
    const NAME = 'character';
    const DBTYPE = 'bpchar';
}
