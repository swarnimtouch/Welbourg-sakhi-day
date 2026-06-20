<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DoctorController extends Controller
{
    public function index()
    {
        return view('doctor.index');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_code' => ['required', 'string', 'max:255'],
            'doctor_name' => ['required', 'string', 'max:255'],
            'cropped_image' => ['required', 'string'],
        ]);

        $baseFolder = 'Welbourg-sakhi-day';
        $slug = Str::slug($data['doctor_name']) ?: 'employee';
        $timestamp = now()->format('YmdHisv');
        $photoFile = $slug . '_' . $timestamp . '.png';
        $bannerFile = $slug . '_' . $timestamp . '_banner.png';

        $encodedImage = preg_replace('/^data:image\/[a-zA-Z0-9.+-]+;base64,/', '', $data['cropped_image']);
        $imageData = base64_decode(str_replace(' ', '+', $encodedImage), true);
        $employeeImage = $imageData !== false ? @imagecreatefromstring($imageData) : false;

        if ($employeeImage === false) {
            return back()->withErrors(['cropped_image' => 'Please upload a valid photo.'])->withInput();
        }

        $backgroundPath = public_path('uploads/images/WB_Fathers_day_card_photo_circle_upper.png');
        $banner = @imagecreatefrompng($backgroundPath);

        if ($banner === false) {
            imagedestroy($employeeImage);
            return back()->withErrors(['cropped_image' => 'Card template could not be loaded.'])->withInput();
        }

        // Photo placement supplied for the Father's Day card.
        $photoX = 258;
        $photoY = 370;
        $photoWidth = 485;
        $photoHeight = 511;

        $resized = imagecreatetruecolor($photoWidth, $photoHeight);
        imagealphablending($resized, false);
        imagesavealpha($resized, true);
        imagefill($resized, 0, 0, imagecolorallocatealpha($resized, 0, 0, 0, 127));
        imagecopyresampled(
            $resized,
            $employeeImage,
            0,
            0,
            0,
            0,
            $photoWidth,
            $photoHeight,
            imagesx($employeeImage),
            imagesy($employeeImage)
        );

        imagealphablending($banner, true);
        imagecopy($banner, $resized, $photoX, $photoY, 0, 0, $photoWidth, $photoHeight);

        // Keep the name centered inside the card's name box and shrink long names.
        $font = public_path('fonts/RobotoCondensed-Bold.ttf');
        $fontSize = 42;
        $nameBoxX = 285;
        $nameBoxY = 908;
        $nameBoxWidth = 444;
        $nameBoxHeight = 111;
        $horizontalPadding = 24;
        $verticalPadding = 16;

        while ($fontSize > 14) {
            $textBox = imagettfbbox($fontSize, 0, $font, $data['doctor_name']);
            $textWidth = $textBox[2] - $textBox[0];
            $textHeight = $textBox[1] - $textBox[7];

            if (
                $textWidth <= ($nameBoxWidth - $horizontalPadding * 2)
                && $textHeight <= ($nameBoxHeight - $verticalPadding * 2)
            ) {
                break;
            }
            $fontSize--;
        }

        $textBox = imagettfbbox($fontSize, 0, $font, $data['doctor_name']);
        $textWidth = $textBox[2] - $textBox[0];
        $textHeight = $textBox[1] - $textBox[7];
        $nameX = (int) round($nameBoxX + (($nameBoxWidth - $textWidth) / 2) - $textBox[0]);
        $nameY = (int) round($nameBoxY + (($nameBoxHeight - $textHeight) / 2) - $textBox[7]);

        $nameColor = imagecolorallocate($banner, 21, 73, 109);
        imagettftext($banner, $fontSize, 0, $nameX, $nameY, $nameColor, $font, $data['doctor_name']);

        ob_start();
        imagepng($banner, null, 0);
        $bannerData = ob_get_clean();

        Storage::disk('s3')->put($baseFolder . '/photos/' . $photoFile, $imageData, 'public');
        Storage::disk('s3')->put($baseFolder . '/banners/' . $bannerFile, $bannerData, 'public');

        imagedestroy($banner);
        imagedestroy($employeeImage);
        imagedestroy($resized);

        $photoPath = $baseFolder . '/photos/' . $photoFile;
        $bannerPath = $baseFolder . '/banners/' . $bannerFile;

        Doctor::create([
            'employee_code' => $data['employee_code'],
            'doctor_name' => $data['doctor_name'],
            'doctor_photo' => $photoPath,
            'doctor_banner_path' => $bannerPath,
            'employee_name' => null,
            'employee_hq' => null,
            'doctor_prefix' => null,
            'doctor_qualification' => null,
            'doctor_phone' => null,
        ]);

        return redirect()->route('doctor.index')
            ->with('success', 'Card generated successfully!')
            ->with('banner_path', $bannerPath);
    }

    public function downloadPdf($file)
    {
        $path = 'Welbourg-sakhi-day/banners/' . $file;
        $base64 = base64_encode(Storage::disk('s3')->get($path));
        $html = '<div style="text-align:center;"><img src="data:image/png;base64,' . $base64 . '" style="width:100%;"></div>';

        return Pdf::loadHTML($html)
            ->setPaper('a4', 'portrait')
            ->download(str_replace('.png', '.pdf', $file));
    }
}
