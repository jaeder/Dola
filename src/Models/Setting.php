<?php

namespace DFZ\Dola\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'dola_settings';

    protected $guarded = [];

    public $timestamps = false;
}
