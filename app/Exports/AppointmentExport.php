<?php

namespace App\Exports;

use App\Models\Operational\Appointment;
use Maatwebsite\Excel\Concerns\FromCollection;

class AppointmentExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // protected $id;

    // public function __construct($id)
    // {
    //     $this->id = $id;
    // }

    public function collection()
    {
        return Appointment::all();
        // return Appointment::where('id', $this->id)->get();
    }
}
