<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welbourg Sakhi Day</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Croppie CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css"/>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

    <!-- jQuery, Croppie & jQuery Validate -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

    <style>
        :root {
            /* Brand Colors Extracted from Logo */
            --theme-navy: #1D507B;
            --theme-teal: #03B8A5;
            --theme-navy-hover: #143b5c;
            --theme-teal-hover: #029485;
            --bg-color: #f4f8fb;
            --text-dark: #1a202c;
            --text-muted: #4a5568;
            --error-red: #e53e3e;
        }

        body {
            font-family: 'Nunito', sans-serif;
            /* Premium light background: form se thoda sa dark aur soft gradient */
          background: linear-gradient(135deg, #d6e1f3 0%, #bbd3ec 50%, #ade9d9 100%);
            background-size: 200% 200%;
            animation: gradientBG 12s ease infinite; /* Soft moving background */
            min-height: 100vh;
            position: relative;
            padding: 40px 16px;
        }

        /* Top Left Main Logo */
        .page-logo {
            position: absolute;
            top: 24px;
            left: 32px;
        }
        .page-logo img {
            max-height: 100px; /* 🔴 NAYA: 45px se badha kar 75px kiya */
            width: auto;
            object-fit: contain;
        }
        /* Main Form Card */
        .custom-card {
            background: #fff; /* Pure white on light background */
            border-radius: 24px;
            /* Normal soft dark shadow for light background */
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            padding: 40px 32px;
            border-top: 5px solid var(--theme-teal);
            animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1); /* Entrance animation */
        }

        /* Form Header */
        .form-header {
            text-align: center;
            margin-bottom: 28px;
        }
        .form-header img {
            max-height: 50px;
            margin-bottom: 12px;
            object-fit: contain;
        }
        .form-header h2 {
            font-size: 24px;
            font-weight: 800;
            color: var(--theme-navy);
            letter-spacing: -0.3px;
        }

        /* Success Banner */
        .success-banner {
            background: #e6f7f6;
            border: 1.5px solid var(--theme-teal);
            color: var(--theme-teal-hover);
            border-radius: 10px;
            padding: 14px 18px;
            margin-bottom: 22px;
            font-weight: 700;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Form Labels & Inputs */
        label {
            font-size: 13px;
            font-weight: 700;
            color: var(--theme-navy);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control {
            padding: 12px 16px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 15px;
            color: var(--text-dark);
            background: #fafafa;
            transition: all 0.2s;
        }
        .form-control:focus {
            border-color: var(--theme-teal);
            box-shadow: 0 0 0 3px rgba(3, 184, 165, 0.15);
            background: #fff;
        }

        /* Error States */
        .input-error { border-color: var(--error-red) !important; background: #fff5f5 !important; }
        div.error {
            color: var(--error-red);
            font-size: 12px;
            margin-top: 6px;
            font-weight: 600;
        }

        .divider { border-top: 1.5px solid #e2e8f0; margin: 24px 0; opacity: 1; }

        /* --------------------------------------
           UPLOAD & PREVIEW AREA (REACT STYLE)
           -------------------------------------- */
        .photo-label {
            font-size: 13px;
            font-weight: 700;
            color: var(--theme-navy);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
        }

        .upload-area {
            border: 2px dashed #cbd5e0;
            border-radius: 12px;
            padding: 24px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            background: #f8fafc;
            margin-bottom: 16px;
            position: relative;
        }
        .upload-area:hover { border-color: var(--theme-teal); background: #f0fdfa; }
        .upload-area.area-error { border-color: var(--error-red); background: #fff5f5; }
        .upload-area input[type="file"] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
        }

        .upload-icon {
            color: var(--theme-teal);
            transition: transform 0.3s;
        }
        .upload-area:hover .upload-icon {
            transform: scale(1.1);
        }

        /* In-Box Preview */
        .preview-area {
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            background: #fff;
            margin-bottom: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            text-align: center;
            animation: fadeIn 0.4s ease;
        }

        #crop-preview-img {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            border: 3px solid var(--theme-teal);
            object-fit: cover;
            box-shadow: 0 4px 14px rgba(0,0,0,0.1);
            margin-bottom: 16px;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 16px;
            font-weight: 700;
            font-size: 13px;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .btn-recrop {
            background: rgba(3, 184, 165, 0.1);
            color: var(--theme-teal-hover);
            border: 1px solid var(--theme-teal);
        }
        .btn-recrop:hover {
            background: var(--theme-teal);
            color: #fff;
            transform: translateY(-2px);
        }

        .btn-discard {
            background: rgba(229, 62, 62, 0.1);
            color: var(--error-red);
            border: 1px solid var(--error-red);
        }
        .btn-discard:hover {
            background: var(--error-red);
            color: #fff;
            transform: translateY(-2px);
        }

        /* Buttons */
        .btn-submit {
            padding: 14px;
            background: linear-gradient(90deg, var(--theme-navy), var(--theme-teal));
            background-size: 200% auto;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 800;
            transition: all 0.4s ease;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(3, 184, 165, 0.3);
        }
        .btn-submit:hover {
            background-position: right center;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(3, 184, 165, 0.4);
        }
        .btn-submit:disabled { background: #94a3b8; cursor: not-allowed; }

        .btn-download-wrap {
            margin-top: 24px;
            text-align: center;
            padding-top: 20px;
            border-top: 1.5px dashed #e2e8f0;
        }
        .btn-download {
            background: var(--theme-teal);
            color: #fff;
            padding: 13px 28px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 800;
            border: none;
            transition: background 0.2s;
        }
        .btn-download:hover { background: var(--theme-teal-hover); color: white; }

        /* Modal Styles */
        .modal-content {
            border-radius: 16px;
            border: none;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        .modal-header {
            border-bottom: 1.5px solid #e2e8f0;
            background: #f8fafc;
            border-radius: 16px 16px 0 0;
        }
        .modal-title {
            font-weight: 800;
            color: var(--theme-navy);
        }
        .cropper-container-wrapper {
            width: 100%;
            /* background: #000; Ye yaha se nikal denge, croppie khud black space lega */
            border-radius: 8px;
            /* Extra space bottom me add kiya slider ke liye (white area me) */
            padding-bottom: 30px;
            /* Relative zaruri hai taki andar ka absolute slider iske bahar nikal sake */
            position: relative;
        }

        /* NAYA CSS ADD KARE: Zoom Slider ko black box ke bahar nikalne ke liye */
        .cr-slider-wrap {
            position: absolute !important;
            bottom: -25px !important; /* Slider ko image boundary se 25px niche dhakela */
            left: 50% !important;
            transform: translateX(-50%) !important;
            width: 80% !important;
            margin: 0 !important;
            z-index: 10 !important;
        }

        /* Jo black color pehle pure wrapper me tha, wo sirf image area me diya */
        .croppie-container .cr-boundary {
            background-color: #000;
            border-radius: 8px; /* Optional: Agar black box ke corners round chahiye */
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* --- NAYE ANIMATIONS YAHAN SE ADD KAREIN --- */

        /* Background gradient move karne ke liye */
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Top logo ko float karwane ke liye */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
            100% { transform: translateY(0px); }
        }

        /* Card ko niche se smooth upar laane ke liye */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsive */
        /* Responsive */
        @media (max-width: 1025px) {
            body {
                display: block;
                padding-top: 30px; /* Body ki top padding adjust ki */
            }
            .page-logo {
                position: relative; /* Absolute hata diya taki form ke upar overlap na ho */
                top: 0;
                left: 0;
                transform: none; /* Purana translate reset kiya */
                text-align: center; /* Logo ko horizontally center karne ke liye */
                margin-bottom: 25px; /* Logo aur form ke beech ki space */
                width: 100%;
            }
            .page-logo img {
                max-height: 85px; /* Choti screen par size thoda adjust kiya taki ajeeb na lage */
            }
            .custom-card {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>

<!-- Top Left Page Logo -->
<div class="page-logo">
    <img src="{{ asset('images/logo.png') }}" alt="Welbourg Logo">
</div>

<div class="container d-flex justify-content-center">
    <div class="custom-card w-100" style="max-width: 560px;">

        <!-- Form Logo & Centered Heading -->
        <div class="form-header">
            <img src="{{ asset('images/form_logo.png') }}" alt="Form Logo">
            <h2>Doctor Registration</h2>
        </div>

        {{-- ✅ Success Message — TOP --}}
        @if(session('success'))
            <div class="success-banner">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="12" fill="var(--theme-teal)"/>
                    <path d="M7 12.5l3.5 3.5 6.5-7" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- ── Form ── --}}
        <form id="doctorForm" method="POST" action="{{ route('doctor.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-3 position-relative">
                <label>Employee Name <span style="color:var(--error-red)">*</span></label>
                <input type="text" class="form-control" id="employee_name" name="employee_name" value="{{ old('employee_name') }}" placeholder="Enter employee name">
            </div>

            <div class="mb-3 position-relative">
                <label>Employee Code <span style="color:#a0aec0; font-weight:600; text-transform:none">(Optional)</span></label>
                <input type="text" class="form-control" name="employee_code" value="{{ old('employee_code') }}" placeholder="Enter employee code">
            </div>

            <div class="mb-3 position-relative">
                <label>HQ <span style="color:var(--error-red)">*</span></label>
                <input type="text" class="form-control" id="employee_hq" name="employee_hq" value="{{ old('employee_hq') }}" placeholder="Enter HQ location">
            </div>

            <hr class="divider">
            <div class="mb-3 position-relative">
                <label>Prefix <span style="color:var(--error-red)">*</span></label>
                <select name="doctor_prefix" class="form-control">
                    <option value="">Select Prefix</option>
                    <option value="Dr." selected>Dr.</option>
                </select>
            </div>

            <div class="mb-3 position-relative">
                <label>Doctor Name <span style="color:var(--error-red)">*</span></label>
                <input type="text" class="form-control" id="doctor_name" name="doctor_name" value="{{ old('doctor_name') }}" placeholder="Enter doctor name">
            </div>

            <div class="mb-3 position-relative">
                <label>Qualification <span style="color:var(--error-red)">*</span></label>
                <input type="text" class="form-control" id="doctor_qualification" name="doctor_qualification" value="{{ old('doctor_qualification') }}" placeholder="e.g. MBBS, MD">
            </div>

            <div class="mb-4 position-relative">
                <label>Phone <span style="color:var(--error-red)">*</span></label>
                <input type="tel" class="form-control" id="doctor_phone" name="doctor_phone"
                       value="{{ old('doctor_phone') }}"
                       placeholder="10-digit phone number"
                       maxlength="10"
                       inputmode="numeric"
                       pattern="[0-9]*">
            </div>

            <hr class="divider">

            <div class="photo-label">Doctor Photo <span style="color:var(--error-red)">*</span></div>

            <!-- UPLOAD STATE -->
            <div class="upload-area" id="uploadArea">
                <input type="file" id="upload" accept="image/*">
                <div class="upload-icon mb-2">
                    <svg viewBox="0 0 24 24" width="40" height="40" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M7 10V9C7 6.23858 9.23858 4 12 4C14.7614 4 17 6.23858 17 9V10C19.2091 10 21 11.7909 21 14C21 15.4806 20.1956 16.8084 19 17.5M7 10C4.79086 10 3 11.7909 3 14C3 15.4806 3.8044 16.8084 5 17.5M7 10C7.43285 10 7.84965 10.0688 8.24254 10.1948M12 12V21M12 12L15 15M12 12L9 15"></path>
                    </svg>
                </div>
                <p class="mb-1" style="font-weight: 700; color: var(--text-dark);">Click to Upload Photo</p>
                <p class="mb-0" style="font-size: 13px; color: var(--text-muted);">Crop your face clearly for the best result.</p>
            </div>

            <!-- PREVIEW STATE (Hidden initially) -->
            <div class="preview-area" id="previewArea" style="display: none;">
                <img id="crop-preview-img" src="" alt="Cropped Preview">
                <div class="d-flex justify-content-center gap-3">
                    <button type="button" class="btn btn-action btn-recrop" id="btnRecrop">
                        <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2"><path d="M6.13 1L6 16a2 2 0 0 0 2 2h15"></path><path d="M1 6.13L16 6a2 2 0 0 1 2 2v15"></path></svg>
                        Re-Crop
                    </button>
                    <button type="button" class="btn btn-action btn-discard" id="btnDiscard">
                        <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                        Discard
                    </button>
                </div>
            </div>


            <input type="hidden" name="cropped_image" id="cropped_image">

            <button type="submit" class="btn btn-submit w-100 mt-2" id="submitBtn">Submit &amp; Generate Banner</button>
        </form>

        @if(session('banner_path'))
            <div class="btn-download-wrap d-flex gap-2">

                <!-- IMAGE DOWNLOAD -->
                <button class="btn btn-download d-inline-flex align-items-center" id="downloadBtn"
                        data-url="{{ Storage::disk('s3')->url(session('banner_path')) }}"
                        data-name="{{ basename(session('banner_path')) }}">
                    Download Banner
                </button>

                <!-- PDF DOWNLOAD -->
                <a href="{{ route('download.pdf', basename(session('banner_path'))) }}"
                   class="btn btn-danger d-inline-flex align-items-center">

                    Download PDF
                </a>

            </div>
        @endif

    </div>
</div>

<!-- CROP MODAL (Bootstrap) -->
<div class="modal fade" id="cropModal" tabindex="-1" aria-labelledby="cropModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cropModalLabel">Adjust Your Photo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-3 text-center">
        <div class="cropper-container-wrapper">
            <div id="croppie-container"></div>
        </div>
      </div>
      <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-submit m-0 px-4 py-2" id="cropBtn" style="font-size: 14px;">Save & Crop</button>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(function () {
        // Validation & Modal Setup
        let croppieInstance = null;
        let originalImageSrc = null;
        let savedCropData = null; // Saves state for Re-Crop
        const cropModalEl = document.getElementById('cropModal');
        const cropModal = new bootstrap.Modal(cropModalEl);

        // ─────────────────────────────
        // ✅ IMAGE UPLOAD & CROP LOGIC
        // ─────────────────────────────
        $('#upload').on('change', function () {
            if (!this.files || !this.files[0]) return;

            let reader = new FileReader();
            reader.onload = function (e) {
                originalImageSrc = e.target.result;
                savedCropData = null; // Reset crop data for new image
                cropModal.show();
            };
            reader.readAsDataURL(this.files[0]);
        });

        // Initialize Croppie ONLY when modal is fully visible to avoid dimension bugs
        cropModalEl.addEventListener('shown.bs.modal', function () {
            if (!croppieInstance) {
                croppieInstance = $('#croppie-container').croppie({
                    viewport: { width: 250, height: 250, type: 'circle' },
                    boundary: { width: '100%', height: 350 },
                    enableZoom: true,
                    showZoomer: true // ✅ Ye explicitly zoom slider line ko UI me display karega
                });
            }

            // Bind image and apply saved state if re-cropping
            croppieInstance.croppie('bind', {
                url: originalImageSrc,
                points: savedCropData ? savedCropData.points : undefined,
                zoom: savedCropData ? savedCropData.zoom : undefined
            });
        });

        // Handle Modal Close (Cancel without cropping)
        cropModalEl.addEventListener('hidden.bs.modal', function () {
            // If they closed modal and no image was ever cropped, reset file input
            if (!$('#cropped_image').val()) {
                $('#upload').val('');
            }
        });

        // Save & Crop Action
        // Save & Crop Action
        $('#cropBtn').on('click', function () {
            if (!croppieInstance) return;

            // Save state so "Re-Crop" remembers position
            savedCropData = croppieInstance.croppie('get');

            croppieInstance.croppie('result', {
                type: 'base64',
                size: { width: 502, height: 502 },
                format: 'png',
                quality: 1,
                circle: true // 🔴 NAYA CHANGE: Isko true karne se cropped base64 perfectly round aayega with transparent corners
            }).then(function (img) {
                // Set form data & preview
                $('#cropped_image').val(img);
                $('#crop-preview-img').attr('src', img);

                // Toggle UI
                $('#uploadArea').hide();
                $('#previewArea').fadeIn();

                // Clear Errors
                $('#err_cropped_image').hide();
                $('#uploadArea').removeClass('area-error');

                cropModal.hide();
            });
        });

        // Re-Crop Action
        $('#btnRecrop').on('click', function() {
            if(originalImageSrc) {
                cropModal.show();
            }
        });

        // Discard Action
        $('#btnDiscard').on('click', function() {
            originalImageSrc = null;
            savedCropData = null;
            $('#cropped_image').val('');
            $('#crop-preview-img').attr('src', '');
            $('#upload').val('');

            $('#previewArea').hide();
            $('#uploadArea').fadeIn();
        });


        // ─────────────────────────────
        // ✅ JQUERY VALIDATION
        // ─────────────────────────────
        $('#doctorForm').validate({
            ignore: [], /* 🔴 NAYA ADD: Taki hidden '#cropped_image' ko ignore na kare */
            errorElement: 'div',
            errorClass: 'error',
            highlight: function(element) {
                $(element).addClass('input-error');

                // 🔴 NAYA: Agar error photo (cropped_image) me hai, toh upload box ka border red karo
                if (element.name === "cropped_image") {
                    $('#uploadArea').addClass('area-error');
                }
            },
            unhighlight: function(element) {
                $(element).removeClass('input-error');

                // 🔴 NAYA: Jab photo upload ho jaye, toh red border nikal do
                if (element.name === "cropped_image") {
                    $('#uploadArea').removeClass('area-error');
                }
            },
            errorPlacement: function(error, element) {
                // 🔴 NAYA: Photo ka error message theek upload box ke niche dikhane ke liye
                if (element.attr("name") == "cropped_image") {
                    error.insertAfter("#uploadArea");
                } else {
                    // Baki sab inputs ke liye default jagah par error dikhayega
                    error.insertAfter(element);
                }
            },
            rules: {
                employee_name: { required: true },
                employee_hq: { required: true },
                doctor_name: { required: true },
                doctor_qualification: { required: true },
                doctor_phone: {
                    required: true,
                    digits: true,
                    minlength: 10,
                    maxlength: 10
                },
                cropped_image: { required: true } /* 🔴 NAYA ADD: Photo field ab mandatory hai */
            },
            messages: {
                employee_name: "Employee name is required.",
                employee_hq: "HQ is required.",
                doctor_name: "Doctor name is required.",
                doctor_qualification: "Qualification is required.",
                doctor_phone: "Enter a valid 10-digit phone number.",
                cropped_image: "Doctor Photo is required." /* 🔴 NAYA ADD */
            },
            submitHandler: function(form) {
                // 🔴 NAYA: Ab manual if-else check ki zarurat nahi hai kyuki validation plugin khud red border aur error handle kar raha hai
                $('#submitBtn').prop('disabled', true).text('Processing...');
                form.submit();
            }
        });

        // ─────────────────────────────
        // ✅ DOWNLOAD BUTTON (S3 FIX)
        // ─────────────────────────────
        $(document).on('click', '#downloadBtn', function () {
            let url = $(this).data('url');
            let fileName = $(this).data('name');

            if (!url) return;

            let btn = $(this);
            let originalText = btn.html();
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

                    btn.html(originalText).prop('disabled', false);
                })
                .catch(err => {
                    console.error('Download failed:', err);
                    btn.html(originalText).prop('disabled', false);
                });
        });

        // ─────────────────────────────
        // 📱 PHONE VALIDATION (Key Filters)
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
            $(this).valid();
        });

        $('#doctor_phone').on('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
        });

    });
</script>

</body>
</html>
