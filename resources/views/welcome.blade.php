<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskTopia - Login</title>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/landingpage.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.13/dist/sweetalert2.min.js"></script>
</head>

<body>
    <div class="image-container">
        <div class="text-overlay">
            <h1>TaskTopia</h1>
            <p>Your ultimate solution for task management and productivity, helping you organize tasks efficiently and enhance team collaboration.</p>
        </div>
        <img src="/image/background.png" alt="Description of image">
        <div class="shadow-circle"></div>
    </div>

    <div class="wrapper">
        <div class="title-text">
            <div class="title login">Login Form</div>
            <div class="title signup">Signup Form</div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-container">
            <div class="slide-controls">
                <input type="radio" name="slide" id="login" checked>
                <input type="radio" name="slide" id="signup">
                <label for="login" class="slide login">Login</label>
                <label for="signup" class="slide signup">Signup</label>
                <div class="slider-tab"></div>
            </div>
            <div class="form-inner">
                <form action="{{ route('auth.handle') }}" method="POST" class="login">
                    @csrf
                    <div class="field">
                        <input type="text" name="email" placeholder="Email Address" required>
                    </div>
                    <div class="field">
                        <input type="password" name="password" placeholder="Password" required>
                    </div>
                    <input type="hidden" name="login" value="1">
                    <div class="pass-link">
                        <a href="{{ route('password.request') }}">Forgot password?</a>
                    </div>
                    <div class="field btn">
                        <div class="btn-layer"></div>
                        <input type="submit" value="Login">
                    </div>
                    <div class="signup-link">
                        Not a member? <a href="#" onclick="document.getElementById('signup-form').submit(); return false;">Signup now</a>
                    </div>
                </form>

                <form action="{{ route('auth.handle') }}" method="POST" id="signup-form" class="signup">
                    @csrf
                    <div class="field">
                        <input type="text" name="name" placeholder="Full Name" required>
                    </div>
                    <div class="field">
                        <input type="text" name="email" placeholder="Email Address" required>
                    </div>
                    <div class="field">
                        <input type="password" name="password" placeholder="Password" required>
                    </div>
                    <div class="field">
                        <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
                    </div>
                    <input type="hidden" name="register" value="1">
                    <div class="field btn">
                        <div class="btn-layer"></div>
                        <input type="submit" value="Signup">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="successMessage" class="success-message" style="display:none;">
        <p id="successText"></p>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/landingpage.js') }}"></script>
</body>
</html>