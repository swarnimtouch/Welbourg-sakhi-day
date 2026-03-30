<?php
namespace App\Exports;

use App\Models\Doctor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Storage;

class DoctorExport implements FromCollection, WithHeadings
{
    protected $search;

    public function __construct($search = null)
    {
        $this->search = $search;
    }

    public function collection()
    {
        return Doctor::when($this->search, function ($q) {
            $q->where('doctor_name', 'like', '%' . $this->search . '%');
        })
            ->get()
            ->map(function ($doctor) {

                return [
                    'employee_name'        => $doctor->employee_name,
                    'employee_code'        => $doctor->employee_code,
                    'employee_hq'          => $doctor->employee_hq,
                    'doctor_name'          => $doctor->doctor_prefix.''.$doctor->doctor_name,
                    'doctor_qualification' => $doctor->doctor_qualification,
                    'doctor_phone'         => $doctor->doctor_phone,

                    'photo_url' => $doctor->doctor_photo
                        ? Storage::disk('s3')->url($doctor->doctor_photo)
                        : '',

                    'banner_url' => $doctor->doctor_banner_path
                        ? Storage::disk('s3')->url($doctor->doctor_banner_path)
                        : '',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Employee Name',
            'Employee Code',
            'Employee HQ',
            'Doctor Name',
            'Qualification',
            'Phone',
            'Photo URL',
            'Banner URL',
        ];
    }
}
