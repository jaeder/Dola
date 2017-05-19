<?php

namespace DFZ\Dola\Models;

use Illuminate\Database\Eloquent\Model;
use DFZ\Dola\Facades\Dola;

class Role extends Model
{
    protected $guarded = [];

    protected $table = 'roles';

    public function users()
    {
        return $this->belongsToMany(Dola::modelClass('User'), 'user_roles');
    }

    public function permissions()
    {
        return $this->belongsToMany(Dola::modelClass('Permission'));
    }
}
