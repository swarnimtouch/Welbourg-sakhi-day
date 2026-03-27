<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class DoctorController extends Controller
{
    public function index()
    {
        return view('doctor.index');
    }

    public function store(Request $request)
    {
        // ✅ Validation
        $request->validate([
            'employee_name'        => 'required',
            'employee_hq'          => 'required',
            'doctor_name'          => 'required',
            'doctor_qualification' => 'required',
            'doctor_phone'         => 'required|digits:10',
            'cropped_image'        => 'required',
        ]);

        $data = $request->all();

        // ✅ Clean doctor name
        $doctorSlug = Str::slug($data['doctor_name']);

        // ✅ HARDCODE S3 FOLDER 🔥
        $baseFolder = 'Welbourg-sakhi-day';

        $imageName = null;
        $finalName = null;

        if (!empty($data['cropped_image'])) {

            // ── Base64 Clean ──
            $image = preg_replace('/^data:image\/\w+;base64,/', '', $data['cropped_image']);
            $image = str_replace(' ', '+', $image);
            $imageData = base64_decode($image);

            // ✅ File names
            $imageName = $doctorSlug . '_' . time() . '.png';
            $bannerName = $doctorSlug . '_' . time() . '_banner.png';

            // ── Upload Doctor Photo to S3 ──
            Storage::disk('s3')->put(
                $baseFolder . '/photos/' . $imageName,
                $imageData,
                'public'
            );

            // ── TEMP: create image resource from string ──
            $doctorImage = imagecreatefromstring($imageData);

            // ── Load Banner Background (LOCAL) ──
            $bgPath = public_path('uploads/images/background.jpg');
            $banner = imagecreatefromjpeg($bgPath);

            // 🎯 SETTINGS
            $size  = 502;
            $destX = 291;
            $destY = 174;

            // Resize
            $resized = imagecreatetruecolor($size, $size);
            imagealphablending($resized, false);
            imagesavealpha($resized, true);

            $transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
            imagefill($resized, 0, 0, $transparent);

            imagecopyresampled(
                $resized,
                $doctorImage,
                0, 0,
                0, 0,
                $size, $size,
                imagesx($doctorImage),
                imagesy($doctorImage)
            );

            // Merge on banner
            imagecopy($banner, $resized, $destX, $destY, 0, 0, $size, $size);

            // ── Convert Banner to String (IMPORTANT 🔥) ──
            ob_start();
            imagepng($banner, null, 0);
            $bannerData = ob_get_clean();

            // ── Upload Banner to S3 ──
            Storage::disk('s3')->put(
                $baseFolder . '/banners/' . $bannerName,
                $bannerData,
                'public'
            );

            // Cleanup
            imagedestroy($banner);
            imagedestroy($doctorImage);
            imagedestroy($resized);

            // Save names (with path)
            $imageName = $baseFolder . '/photos/' . $imageName;
            $finalName = $baseFolder . '/banners/' . $bannerName;
        }

        // ✅ Save DB
        Doctor::create([
            'employee_name'        => $data['employee_name'],
            'employee_code'        => $data['employee_code'] ?? null,
            'employee_hq'          => $data['employee_hq'],
            'doctor_name'          => $data['doctor_name'],
            'doctor_qualification' => $data['doctor_qualification'],
            'doctor_phone'         => $data['doctor_phone'],
            'doctor_photo'         => $imageName,
            'doctor_banner_path'   => $finalName,
        ]);

        return redirect()->route('doctor.index')
            ->with('success', 'Doctor saved successfully!')
            ->with('banner_path', $finalName);
    }
}
