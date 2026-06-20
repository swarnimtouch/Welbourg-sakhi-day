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
            $q->where(function ($query) {
                $query->where('doctor_name', 'like', '%' . $this->search . '%')
                    ->orWhere('employee_code', 'like', '%' . $this->search . '%');
            });
        })
            ->get()
            ->map(function ($doctor) {

                return [
                    'employee_code'        => $doctor->employee_code,
                    'name' => $doctor->doctor_name,
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
            'Employee Code',
            'Name',
            'Photo URL',
            'Banner URL',
        ];
    }
}
