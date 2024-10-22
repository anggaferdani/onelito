<?php

namespace App\Models\Bot;

use Spatie\Permission\Models\Role as ModelsRole;

class Role extends ModelsRole
{
    protected $connection = 'mysql_bot';
}
