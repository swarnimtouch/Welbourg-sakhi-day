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
            'doctor_prefix' => 'nullable',
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
            $size  = 814;
            $destX = 213;
            $destY = 752;

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
            // ── TEXT SETTINGS ──
            // Font path
            // Fonts
            $fontBold    = public_path('fonts/RobotoCondensed-Bold.ttf');
            $fontRegular = public_path('fonts/RobotoCondensed-Regular.ttf');

            // Colors
            $blueColor = imagecolorallocate($banner, 21, 73, 109);

            // Gradient colors (RED → ORANGE)
            $startColor = [237, 28, 36];
            $endColor   = [255, 127, 39];

            // Data
            $prefix = $data['doctor_prefix'] ?? '';
            $name   = trim($prefix . ' ' . $data['doctor_name']);
            $qual  = trim($data['doctor_qualification']);
            $phone = $data['doctor_phone'];

            // Width limit
            $startX = 1235;
            $endX   = 2480;
            $maxWidth = $endX - $startX;


            // =======================
            // 🎯 AUTO FIT FUNCTION
            // =======================
            function fitTextSize($text, $font, $maxWidth, $startSize) {
                $size = $startSize;

                while ($size > 20) {
                    $box = imagettfbbox($size, 0, $font, $text);
                    $width = $box[2] - $box[0];

                    if ($width <= $maxWidth) {
                        return $size;
                    }
                    $size--;
                }

                return $size;
            }


            // =======================
            // 🎯 NAME (GRADIENT + AUTO FIT)
            // =======================
            // =======================
            // 🎯 NAME (THODA NICHE + BALANCED SIZE)
            // =======================
            $nameSize = fitTextSize($name, $fontBold, $maxWidth, 75); // 👈 thoda kam

            $x = $startX;
            $y = 1360; // 👈 NICHE LAAYA (important fix)

            $letters = str_split($name);
            $total   = count($letters);

            foreach ($letters as $i => $char) {

                $r = $startColor[0] + ($endColor[0] - $startColor[0]) * ($i / $total);
                $g = $startColor[1] + ($endColor[1] - $startColor[1]) * ($i / $total);
                $b = $startColor[2] + ($endColor[2] - $startColor[2]) * ($i / $total);

                $color = imagecolorallocate($banner, $r, $g, $b);

                imagettftext($banner, $nameSize, 0, $x, $y, $color, $fontBold, $char);

                $bbox = imagettfbbox($nameSize, 0, $fontBold, $char);
                $charWidth = $bbox[2] - $bbox[0];

                $x += $charWidth + 1; // 👈 spacing thoda kam (clean look)
            }


// =======================
// 🎯 QUALIFICATION (SIZE KAM + PROPER GAP)
// =======================
            $qualSize = fitTextSize($qual, $fontRegular, $maxWidth, 40); // 👈 kam kiya

            imagettftext(
                $banner,
                $qualSize,
                0,
                $startX,
                1440, // 👈 name ke niche perfect gap
                $blueColor,
                $fontRegular,
                $qual
            );


// =======================
// 🎯 PHONE (THODA BADA)
// =======================
            $phoneText = $phone;

            $phoneSize = fitTextSize($phoneText, $fontBold, $maxWidth, 70); // 👈 bada

            imagettftext(
                $banner,
                $phoneSize,
                0,
                $startX,
                1650,
                $blueColor,
                $fontBold,
                $phoneText
            );
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
            'doctor_prefix'        => $data['doctor_prefix'], // 👈 ADD THIS
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
