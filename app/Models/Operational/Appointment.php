<?php

namespace App\Models\Operational;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    // use HasFactory;
    use SoftDeletes;

    // declare table
    public $table = 'appointment';

    // this field must type date yyyy-mm-dd hh:mm:ss
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id',
        'doctor_id',
        'consultation_id',
        'level',
        'date',
        'time',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // one to one = hasOne + BelongsToOne
    public function transaction()
    {
        // 2 parameters (path model, field foreignkey)
        return $this->hasOne('App\Models\Operational\Transaction', 'appointment_id',);
    }

    // one to many
    public function doctor()
    {
        // 3 parameters (path model, field foreignkey, field primary key from table hasMany/hasOne) 
        return $this->belongsTo('App\Models\Operational\Doctor', 'doctor_id', 'id');
    }

    // one to many
    public function user()
    {
        // 3 parameters (path model, field foreignkey, field primary key from table hasMany/hasOne) 
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    // one to many
    public function consultation_id()
    {
        // 3 parameters (path model, field foreignkey, field primary key from table hasMany/hasOne) 
        return $this->belongsTo('App\Models\MasterData\Consultation', 'consultation_id', 'id');
    }
}
