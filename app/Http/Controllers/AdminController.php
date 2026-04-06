<?php

namespace App\Http\Controllers;

use App\Exports\DoctorExport;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class AdminController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.doctor.index');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid email or password',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    public function dashboard()
    {
        $totalDoctor  = Doctor::count();

        return view('admin.dashboard', compact('totalDoctor'));
    }


    public function doctor(Request $request)
    {
        $doctors = Doctor::when($request->search, function ($q) use ($request) {

            $q->where(function ($query) use ($request) {

                $query->where('doctor_name', 'like', '%' . $request->search . '%')
                    ->orWhere('doctor_qualification', 'like', '%' . $request->search . '%')
                    ->orWhere('doctor_phone', 'like', '%' . $request->search . '%')
                    ->orWhere('employee_name', 'like', '%' . $request->search . '%')
                    ->orWhere('employee_code', 'like', '%' . $request->search . '%')
                    ->orWhere('employee_hq', 'like', '%' . $request->search . '%');

            });

        })
            ->latest()
            ->paginate(10);

        // ✅ Add S3 URLs
        $doctors->getCollection()->transform(function ($doctor) {

            $doctor->photo_url = $doctor->doctor_photo
                ? Storage::disk('s3')->url($doctor->doctor_photo)
                : null;

            $doctor->banner_url = $doctor->doctor_banner_path
                ? Storage::disk('s3')->url($doctor->doctor_banner_path)
                : null;

            return $doctor;
        });

        return view('admin.doctor', compact('doctors'));
    }

    public function doctor_destroy($id)
    {
        $doctor = Doctor::findOrFail($id);

        if ($doctor->doctor_photo && Storage::disk('s3')->exists($doctor->doctor_photo)) {
            Storage::disk('s3')->delete($doctor->doctor_photo);
        }

        if ($doctor->doctor_banner_path && Storage::disk('s3')->exists($doctor->doctor_banner_path)) {
            Storage::disk('s3')->delete($doctor->doctor_banner_path);
        }

        $doctor->delete();

        return back()->with('success', 'Deleted successfully');
    }

    public function doctor_export(Request $request)
    {
        return Excel::download(
            new DoctorExport($request->search),
            'Doctor.xlsx'
        );
    }
    public function downloadBanner($id)
    {
        $doctor = Doctor::findOrFail($id);

        if (!$doctor->doctor_banner_path) {
            abort(404, 'Banner not found.');
        }

        $path = $doctor->doctor_banner_path;

        // Check file exists on S3
        if (!Storage::disk('s3')->exists($path)) {
            abort(404, 'File not found on storage.');
        }

        $fileName  = 'banner_' . ($doctor->doctor_name ? \Str::slug($doctor->doctor_name) : $id) . '.' . pathinfo($path, PATHINFO_EXTENSION);
        $mimeType  = Storage::disk('s3')->mimeType($path);

        return response()->streamDownload(function () use ($path) {
            echo Storage::disk('s3')->get($path);
        }, $fileName, [
            'Content-Type'        => $mimeType,
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }
    public function downloadAllBanners()
    {
        $doctors = Doctor::whereNotNull('doctor_banner_path')->get();

        if ($doctors->isEmpty()) {
            return back()->with('error', 'No banners found.');
        }

        // ✅ ZIP file path (temporary)
        $zipFileName = 'banners_' . now()->format('Ymd_His') . '.zip';
        $zipPath     = storage_path('app/' . $zipFileName);

        $zip = new \ZipArchive();

        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            return back()->with('error', 'Could not create ZIP file.');
        }

        foreach ($doctors as $doctor) {
            $path = $doctor->doctor_banner_path;

            try {
                if (!Storage::disk('s3')->exists($path)) continue;

                $fileContent = Storage::disk('s3')->get($path);
                $ext         = pathinfo($path, PATHINFO_EXTENSION);
                $fileName    = 'banner_' . \Str::slug($doctor->doctor_name) . '.' . $ext;

                // ✅ 'banners/' folder ke andar store hoga ZIP mein
                $zip->addFromString('banners/' . $fileName, $fileContent);

            } catch (\Exception $e) {
                \Log::warning('Banner ZIP skip: ' . $path . ' | ' . $e->getMessage());
                continue;
            }
        }

        $zip->close();

        // ✅ Download karo aur server se delete karo
        return response()->download($zipPath, $zipFileName, [
            'Content-Type' => 'application/zip',
        ])->deleteFileAfterSend(true);
    }


}
