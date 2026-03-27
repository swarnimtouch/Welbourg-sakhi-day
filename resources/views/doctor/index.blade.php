<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welbourg Sakhi Day</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Nunito', sans-serif;
            background: #f0f4f8;
            min-height: 100vh;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 30px 16px;
        }

        .card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
            padding: 36px 32px 32px;
            width: 100%;
            max-width: 560px;
        }

        h2 {
            font-size: 22px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 24px;
            letter-spacing: -0.3px;
        }

        /* Success Banner */
        .success-banner {
            background: #ebfaf2;
            border: 1.5px solid #34c77b;
            color: #1a7a4a;
            border-radius: 10px;
            padding: 14px 18px;
            margin-bottom: 22px;
            font-weight: 600;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .success-banner svg { flex-shrink: 0; }

        /* Form Fields */
        .form-group { margin-bottom: 18px; }

        label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: #4a5568;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        input[type="text"],
        input[type="tel"] {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-size: 15px;
            font-family: inherit;
            color: #1a202c;
            transition: border 0.15s;
            background: #fafafa;
        }
        input[type="text"]:focus,
        input[type="tel"]:focus {
            outline: none;
            border-color: #2563eb;
            background: #fff;
        }
        input.input-error { border-color: #e53e3e !important; }

        .error {
            color: #e53e3e;
            font-size: 12px;
            margin-top: 5px;
            font-weight: 600;
            display: none;
        }
        .error.show { display: block; }

        .divider {
            border: none;
            border-top: 1.5px solid #e2e8f0;
            margin: 24px 0;
        }

        /* Photo Section */
        .photo-label {
            font-size: 13px;
            font-weight: 700;
            color: #4a5568;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }

        .upload-area {
            border: 2px dashed #cbd5e0;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: border-color 0.15s;
            background: #f7fafc;
            margin-bottom: 16px;
            position: relative;
        }
        .upload-area:hover { border-color: #2563eb; }
        .upload-area.area-error { border-color: #e53e3e; }
        .upload-area input[type="file"] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
        }
        .upload-area p { font-size: 14px; color: #718096; }
        .upload-area span { color: #2563eb; font-weight: 700; }

        #croppie-container { display: none; margin-bottom: 14px; }
        #croppie-container.active { display: block; }

        /* Crop Preview */
        #crop-preview-wrap {
            display: none;
            margin: 14px 0 10px;
            text-align: center;
        }
        #crop-preview-wrap.show { display: block; }
        #crop-preview-label {
            font-size: 12px;
            color: #718096;
            margin-bottom: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        #crop-preview-img {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            border: 3px solid #34c77b;
            object-fit: cover;
            box-shadow: 0 2px 12px rgba(0,0,0,0.13);
        }

        .btn-crop {
            display: none;
            width: 100%;
            padding: 11px;
            background: #0f766e;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            margin-bottom: 10px;
            transition: background 0.15s;
        }
        .btn-crop:hover { background: #0d9488; }
        .btn-crop.active { display: block; }

        #crop-status {
            font-size: 12px;
            color: #38a169;
            font-weight: 700;
            text-align: center;
            min-height: 18px;
            margin-bottom: 6px;
        }

        /* Submit */
        .btn-submit {
            width: 100%;
            padding: 13px;
            background: #2563eb;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            margin-top: 8px;
            letter-spacing: 0.2px;
            transition: background 0.15s;
        }
        .btn-submit:hover { background: #1d4ed8; }
        .btn-submit:disabled { background: #93c5fd; cursor: not-allowed; }

        /* Download Button — bottom only, no preview */
        .btn-download-wrap {
            margin-top: 24px;
            text-align: center;
        }
        .btn-download {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #16a34a;
            color: #fff;
            padding: 13px 28px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            text-decoration: none;
            font-family: inherit;
            transition: background 0.18s;
        }
        .btn-download:hover { background: #15803d; }
    </style>
</head>
<body>

<div class="card">

    <h2>🩺 Doctor Registration</h2>

    {{-- ✅ Success Message — TOP --}}
    @if(session('success'))
        <div class="success-banner">
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="12" fill="#34c77b"/>
                <path d="M7 12.5l3.5 3.5 6.5-7" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- ── Form ── --}}
    <form id="doctorForm" method="POST" action="{{ route('doctor.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label>Employee Name <span style="color:#e53e3e">*</span></label>
            <input type="text" id="employee_name" name="employee_name" value="{{ old('employee_name') }}" placeholder="Enter employee name">
            <div class="error" id="err_employee_name">Employee name is required.</div>
        </div>

        <div class="form-group">
            <label>Employee Code <span style="color:#a0aec0; font-weight:400">(Optional)</span></label>
            <input type="text" name="employee_code" value="{{ old('employee_code') }}" placeholder="Enter employee code">
        </div>

        <div class="form-group">
            <label>HQ <span style="color:#e53e3e">*</span></label>
            <input type="text" id="employee_hq" name="employee_hq" value="{{ old('employee_hq') }}" placeholder="Enter HQ location">
            <div class="error" id="err_employee_hq">HQ is required.</div>
        </div>

        <hr class="divider">

        <div class="form-group">
            <label>Doctor Name <span style="color:#e53e3e">*</span></label>
            <input type="text" id="doctor_name" name="doctor_name" value="{{ old('doctor_name') }}" placeholder="Enter doctor name">
            <div class="error" id="err_doctor_name">Doctor name is required.</div>
        </div>

        <div class="form-group">
            <label>Qualification <span style="color:#e53e3e">*</span></label>
            <input type="text" id="doctor_qualification" name="doctor_qualification" value="{{ old('doctor_qualification') }}" placeholder="e.g. MBBS, MD">
            <div class="error" id="err_doctor_qualification">Qualification is required.</div>
        </div>

        <div class="form-group">
            <label>Phone <span style="color:#e53e3e">*</span></label>
            <input type="tel" id="doctor_phone" name="doctor_phone"
                   value="{{ old('doctor_phone') }}"
                   placeholder="10-digit phone number"
                   maxlength="10"
                   inputmode="numeric"
                   pattern="[0-9]*">
            <div class="error" id="err_doctor_phone">Enter a valid 10-digit phone number.</div>
        </div>

        <hr class="divider">

        <div class="photo-label">Doctor Photo <span style="color:#e53e3e">*</span></div>

        <div class="upload-area" id="uploadArea">
            <input type="file" id="upload" accept="image/*">
            <p>📷 <span>Click to upload</span> or drag a photo here</p>
        </div>

        <div id="croppie-container"></div>

        <button type="button" class="btn-crop" id="cropBtn"> Crop Photo </button>

        <div id="crop-preview-wrap">
            <div id="crop-preview-label">✅ Cropped Preview</div>
            <img id="crop-preview-img" src="" alt="Crop Preview">
        </div>

        <div id="crop-status"></div>

        <input type="hidden" name="cropped_image" id="cropped_image">
        <div class="error" id="err_cropped_image">Please upload and crop a photo first.</div>

        <button type="submit" class="btn-submit" id="submitBtn">Submit &amp; Generate Banner</button>
    </form>

    @if(session('banner_path'))
        <div class="btn-download-wrap">
            <button class="btn btn-success" id="downloadBtn"
                    data-url="{{ Storage::disk('s3')->url(session('banner_path')) }}"
                    data-name="{{ basename(session('banner_path')) }}">
                Download Banner
            </button>
        </div>
    @endif

</div>

<script>
    $(function () {

        var croppieInstance = null;

        // ─────────────────────────────
        // ✅ DOWNLOAD BUTTON (S3 FIX)
        // ─────────────────────────────
        $(document).on('click', '#downloadBtn', function () {

            let url = $(this).data('url');
            let fileName = $(this).data('name');

            if (!url) {
                console.error('No download URL found');
                return;
            }

            let btn = $(this);
            btn.text('Downloading...').prop('disabled', true);

            fetch(url)
                .then(response => response.blob())
                .then(blob => {

                    let blobUrl = window.URL.createObjectURL(blob);

                    let a = document.createElement('a');
                    a.href = blobUrl;
                    a.download = fileName || 'banner.png';

                    document.body.appendChild(a);
                    a.click();

                    a.remove();
                    window.URL.revokeObjectURL(blobUrl);

                    btn.text('Download Banner').prop('disabled', false);
                })
                .catch(err => {
                    console.error('Download failed:', err);
                    btn.text('Download Banner').prop('disabled', false);
                });
        });

        // ─────────────────────────────
        // 📱 PHONE VALIDATION
        // ─────────────────────────────
        $('#doctor_phone').on('keydown', function (e) {
            var allowed = ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Home','End'];
            var isDigit = (e.key >= '0' && e.key <= '9') || (e.keyCode >= 96 && e.keyCode <= 105);

            if (!isDigit && allowed.indexOf(e.key) === -1) {
                e.preventDefault();
                return;
            }

            if (this.value.replace(/[^0-9]/g, '').length >= 10 && isDigit) {
                e.preventDefault();
            }
        });

        $('#doctor_phone').on('paste', function (e) {
            e.preventDefault();
            var pasted = (e.originalEvent.clipboardData || window.clipboardData).getData('text');
            var digits = pasted.replace(/[^0-9]/g, '').slice(0, 10);
            this.value = digits;
        });

        $('#doctor_phone').on('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
        });

        // ─────────────────────────────
        // 📸 IMAGE UPLOAD → CROPPING
        // ─────────────────────────────
        $('#upload').on('change', function () {

            if (!this.files || !this.files[0]) return;

            var reader = new FileReader();

            reader.onload = function (e) {

                if (croppieInstance) {
                    croppieInstance.croppie('destroy');
                    croppieInstance = null;
                }

                $('#croppie-container').addClass('active').html('');
                $('#cropBtn').addClass('active');
                $('#crop-status').text('');
                $('#cropped_image').val('');
                $('#crop-preview-wrap').removeClass('show');
                $('#crop-preview-img').attr('src', '');

                croppieInstance = $('#croppie-container').croppie({
                    viewport: { width: 250, height: 250, type: 'circle' },
                    boundary: { width: 320, height: 320 },
                    enableZoom: true
                });

                croppieInstance.croppie('bind', {
                    url: e.target.result
                });
            };

            reader.readAsDataURL(this.files[0]);
        });

        // ─────────────────────────────
        // ✂ CROP BUTTON
        // ─────────────────────────────
        $('#cropBtn').on('click', function () {

            if (!croppieInstance) return;

            croppieInstance.croppie('result', {
                type: 'base64',
                size: { width: 502, height: 502 },
                format: 'png',
                quality: 1
            }).then(function (img) {

                $('#cropped_image').val(img);

                // Preview
                $('#crop-preview-img').attr('src', img);
                $('#crop-preview-wrap').addClass('show');

                $('#crop-status')
                    .css('color', '#38a169')
                    .text('✅ Photo cropped! Ready to submit.');
            });
        });

        // ─────────────────────────────
        // ✅ FORM VALIDATION
        // ─────────────────────────────
        $('#doctorForm').on('submit', function (e) {

            var valid = true;

            $('.error').removeClass('show');
            $('input').removeClass('input-error');
            $('#uploadArea').removeClass('area-error');

            function requireField(id, errId) {
                var val = $('#' + id).val().trim();
                if (!val) {
                    $('#' + errId).addClass('show');
                    $('#' + id).addClass('input-error');
                    valid = false;
                }
            }

            requireField('employee_name',        'err_employee_name');
            requireField('employee_hq',          'err_employee_hq');
            requireField('doctor_name',          'err_doctor_name');
            requireField('doctor_qualification', 'err_doctor_qualification');

            var phone = $('#doctor_phone').val().replace(/[^0-9]/g, '');
            if (phone.length !== 10) {
                $('#err_doctor_phone').addClass('show');
                $('#doctor_phone').addClass('input-error');
                valid = false;
            }

            if (!$('#cropped_image').val()) {
                $('#err_cropped_image').addClass('show');
                $('#uploadArea').addClass('area-error');
                valid = false;
            }

            if (!valid) {
                e.preventDefault();

                var firstErr = $('.error.show').first();
                if (firstErr.length) {
                    $('html, body').animate({
                        scrollTop: firstErr.offset().top - 120
                    }, 300);
                }

                return false;
            }

            // Disable submit
            $('#submitBtn').prop('disabled', true).text('Processing...');
        });

    });
</script>

</body>
</html>
