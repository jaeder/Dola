<?php

namespace DFZ\Dola\Database\Types\Postgresql;

use DFZ\Dola\Database\Types\Common\VarCharType;

class CharacterVaryingType extends VarCharType
{
    const NAME = 'character varying';
    const DBTYPE = 'varchar';
}
