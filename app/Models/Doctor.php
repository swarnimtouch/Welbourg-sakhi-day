<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    //
    protected $fillable=['employee_name','employee_code','employee_hq','doctor_name','doctor_qualification','doctor_phone','doctor_photo','doctor_banner_path'];
}
