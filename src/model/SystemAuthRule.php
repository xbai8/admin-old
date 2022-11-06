<?php

namespace Hangpu8\Admin\model;

use Hangpu8\Admin\Model;

class SystemAuthRule extends Model
{
    protected $hidden = ['id', 'create_at', 'update_at', 'is_system', 'sort'];
}
