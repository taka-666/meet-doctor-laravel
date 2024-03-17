<?php

namespace App\Models\ManagementAccess;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Permission extends Model
{
    // use HasFactory;
    use SoftDeletes;

    // declare table
    public $table = 'permission';

    // this field must type date yyyy-mm-dd hh:mm:ss
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'title',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function permission_role()
    {
        // 2 parameters (path model, field foreignkey
        return $this->hasMany('App\Models\ManagementAccess\PermissionRole', 'permission_id');
    }
}
