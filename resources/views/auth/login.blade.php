<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zonalta - Admin Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Changed font to Nunito to match index.blade.php -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            /* Brand Colors Extracted from index.blade.php */
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
            /* Premium light gradient background */
            background: linear-gradient(135deg, #d6e1f3 0%, #bbd3ec 50%, #ade9d9 100%);
            background-size: 200% 200%;
            animation: gradientBG 12s ease infinite;
            min-height: 100vh;
            color: var(--text-dark);
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-card {
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border: none;
            border-top: 5px solid var(--theme-teal);
            max-width: 420px;
            width: 100%;
            animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
        }

        .card-header {
            border-bottom: none !important;
        }

        .logo {
            max-height: 60px; /* Adjusted to match form_logo proportions */
            width: auto;
            object-fit: contain;
        }

        .login-title {
            color: var(--theme-navy);
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.3px;
            margin-top: 10px !important;
        }

        .form-label {
            font-size: 13px;
            font-weight: 700;
            color: var(--theme-navy);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .icon-input-wrapper {
            position: relative;
        }

        .icon-input-wrapper .left-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--theme-navy);
            font-size: 14px;
            z-index: 5;
        }

        .icon-input-wrapper .toggle-password {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--theme-navy);
            font-size: 14px;
            cursor: pointer;
            z-index: 5;
            transition: color 0.3s;
        }

        .icon-input-wrapper .toggle-password:hover {
            color: var(--theme-teal);
        }

        .icon-input-wrapper .form-control {
            padding: 12px 16px 12px 45px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 15px;
            color: var(--text-dark);
            background: #fafafa;
            transition: all 0.2s;
            height: auto;
        }

        .icon-input-wrapper #password {
            padding-right: 45px;
        }

        .icon-input-wrapper .form-control:focus {
            border-color: var(--theme-teal);
            box-shadow: 0 0 0 3px rgba(3, 184, 165, 0.15);
            background: #fff;
            outline: none;
        }

        .remember-label {
            font-size: 14px;
            color: var(--text-muted);
            font-weight: 600;
            cursor: pointer;
            margin-bottom: 0;
            margin-left: 8px;
            user-select: none;
        }

        /* Submit Button with matching gradient and animation */
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

        /* Error States matching index.blade.php */
        label.error {
            color: var(--error-red);
            font-size: 12px;
            margin-top: 6px;
            font-weight: 600;
            display: block;
        }

        .form-control.error {
            border-color: var(--error-red) !important;
            background: #fff5f5 !important;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center vh-100 m-0 px-3">

    <div class="card login-card">
        <div class="card-header bg-transparent px-4 pt-4 pb-0 text-center">
            <img src="{{ asset('images/logo.png') }}" alt="Zonalta Logo" class="logo mb-2" onerror="this.style.display='none'">
            <h2 class="login-title">ADMIN LOGIN</h2>
        </div>

        <div class="card-body p-4">

            @if(session('error') || $errors->any())
                <div class="alert alert-danger py-2 px-3 text-center" style="font-size: 14px; border-radius: 10px; font-weight: 600;" role="alert">
                    {{ session('error') ?? $errors->first() }}
                </div>
            @endif

            <form id="adminLoginForm" method="POST" action="{{ route('admin.login.submit') }}" novalidate>
                @csrf

                <div class="mb-4">
                    <label class="form-label" for="email">Email Address</label>
                    <div class="icon-input-wrapper">
                        <i class="fa-solid fa-envelope left-icon"></i>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email Address" value="{{ old('email') }}">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label" for="password">Password</label>
                    <div class="icon-input-wrapper">
                        <i class="fa-solid fa-lock left-icon"></i>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Enter Password">
                        <i class="fa-solid fa-eye-slash toggle-password" title="Show/Hide Password"></i>
                    </div>
                </div>

                <div class="mb-4 d-flex align-items-center">
                    <input type="checkbox" name="remember" id="remember" style="accent-color: var(--theme-teal); width: 16px; height: 16px; cursor: pointer;">
                    <label class="remember-label" for="remember">Remember me</label>
                </div>

                <button type="submit" class="btn btn-submit w-100">Log in</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

    <script>
        $(document).ready(function () {

            $(".toggle-password").click(function() {
                $(this).toggleClass("fa-eye-slash fa-eye");
                var input = $("#password");
                if (input.attr("type") === "password") {
                    input.attr("type", "text");
                } else {
                    input.attr("type", "password");
                }
            });

            $("#adminLoginForm").validate({
                errorElement: 'label',
                errorClass: 'error',
                highlight: function(element) {
                    $(element).addClass('error');
                },
                unhighlight: function(element) {
                    $(element).removeClass('error');
                },
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                        minlength: 6
                    }
                },
                messages: {
                    email: {
                        required: "Please enter your email address.",
                        email: "Please enter a valid email."
                    },
                    password: {
                        required: "Please enter your password.",
                        minlength: "Password must be at least 6 characters."
                    }
                },
                errorPlacement: function(error, element) {
                    error.insertAfter(element.parent(".icon-input-wrapper"));
                },
                submitHandler: function(form) {
                    $('.btn-submit').prop('disabled', true).text('Logging in...');
                    form.submit();
                }
            });
        });
    </script>

</body>
</html>