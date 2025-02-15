<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter OTP - TaskTopia</title>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/landingpage.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin-left: 450px;
            position: relative; /* For fixed positioning of the Home button */
        }

        .wrapper {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
            margin-top: 50px; /* Extra margin for better spacing */
        }

        .title {
            margin-bottom: 20px;
            font-size: 20px;
        }

        .field {
            margin-bottom: 15px;
        }

        .field input {
            width: 50px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-align: center;
            font-size: 18px;
        }

        .field.btn {
            margin-top: 20px;
        }

        .field.btn input[type="submit"] {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        .field.btn input[type="submit"]:hover {
            background-color: #218838;
        }

        .resend-link {
            display: inline-block;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
        }

        .resend-link:hover {
            text-decoration: underline;
        }

        .home-button {
            position: fixed; /* Fixed positioning */
            top: 20px; /* Distance from the top */
            right: 20px; /* Distance from the right */
            background-color: #007bff; /* Button color */
            color: white; /* Text color */
            border: none; /* No border */
            padding: 10px 15px; /* Padding */
            border-radius: 5px; /* Rounded corners */
            cursor: pointer; /* Pointer cursor on hover */
            text-decoration: none; /* No underline */
            font-size: 16px; /* Font size */
            z-index: 1000; /* Ensure it stays on top */
        }

        .home-button:hover {
            background-color: #0056b3; /* Darker shade on hover */
        }

        .alert {
            color: green; /* Green color for success messages */
            margin-top: 15px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <a href="/" class="home-button">Home</a> <!-- Home button -->

    <div class="wrapper">
        <div class="title">Enter OTP</div>

        @if (session('otp_resend_success'))
            <div class="alert">New OTP sent successfully! Please check your email.</div>
        @endif

        <form method="POST" action="{{ route('password.verifyOtp') }}">
            @csrf
            <div class="field">
                <input type="text" name="otp1" maxlength="1" required>
                <input type="text" name="otp2" maxlength="1" required>
                <input type="text" name="otp3" maxlength="1" required>
                <input type="text" name="otp4" maxlength="1" required>
                <input type="text" name="otp5" maxlength="1" required>
                <input type="text" name="otp6" maxlength="1" required>
            </div>
            <div class="field btn">
                <input type="submit" value="Verify OTP">
            </div>
        </form>
        
        <div class="field">
            <a href="{{ route('password.resendOtp') }}" class="resend-link">Resend OTP</a>
        </div>
    </div>

    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route("password.reset.page") }}'; // Redirect to reset password page
                }
            });
        @endif

        // Display SweetAlert for invalid OTP if applicable
        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ implode(', ', $errors->all()) }}',
                confirmButtonText: 'OK'
            });
        @endif
    </script>
</body>

</html>