<?php

namespace App\Models\ManagementAccess;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    // use HasFactory;

    use SoftDeletes;

    // declare table
    public $table = 'role';

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

        // many to many
        public function user()
        {
            return $this->belongsToMany(User::class);
        }
    
        public function permission()
        {
            return $this->belongsToMany('App\Models\ManagementAccess\Permission');
        }

    // one to many
    public function role_user()
    {
        // 2 parameters (path model, field foreignkey
        return $this->hasMany('App\Models\Management\RoleUser', 'role_id');
    }

    public function permission_role()
    {
        // 2 parameters (path model, field foreignkey
        return $this->hasMany('App\Models\ManagementAccess\PermissionRole', 'role_id');
    }
}
