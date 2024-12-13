<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as ModelsPermission;

class Permission extends ModelsPermission
{
    use HasFactory;
}
