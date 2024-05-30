<?php

namespace App\Models\Operational;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Transaction extends Model
{
    // use HasFactory;
    use SoftDeletes;

    // declare table
    public $table = 'transaction';

    // this field must type date yyyy-mm-dd hh:mm:ss
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'appointment_id',
        'fee_hospital',
        'fee_doctor',
        'fee_specialist',
        'sub_total',
        'vat',
        'total',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // one to one
    public function appointment()
    {
        // 3 parameters (path model, field foreignkey, field primary key from table hasMany/hasOne) 
        return $this->belongsTo('App\Models\Operational\Appointment', 'appointment_id', 'id');
    }
}
