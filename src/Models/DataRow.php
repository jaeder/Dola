<?php

namespace Dola\Models;

use Illuminate\Database\Eloquent\Model;

class DataRow extends Model
{
    protected $table = 'dola_data_rows';

    protected $guarded = [];

    public $timestamps = false;
}
