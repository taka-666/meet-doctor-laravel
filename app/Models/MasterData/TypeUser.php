<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class TypeUser extends Model
{
    // use HasFactory;
    use SoftDeletes;

    // declare table
    public $table = 'type_user';

    // this field must type date yyyy-mm-dd hh:mm:ss
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // one to manyy
    // function detail user to type user memiliki relasi detail_user(many) -> type_user(one) 
    public function detail_user()
    {
        // 2 parameters (path model, field foreignkey)
        return $this->hasMany('App\Models\ManagementAccess\DetailUser', 'type_user_id');
    }
}
