<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - TaskTopia</title>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/landingpage.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            display: flex;
            height: 100vh; /* Full viewport height */
            margin: 0;
            font-family: 'Poppins', sans-serif;
            margin-left: 450px;
            position: relative; /* For positioning the button */
        }
        .wrapper {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }

        .title {
            margin-bottom: 20px;
            font-size: 20px;
        }

        .field {
            margin-bottom: 15px;
        }

        .field input {
            width: 100%; /* Full width for inputs */
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
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

        .alert {
            text-align: center;
            margin-bottom: 15px;
            color: red;
        }

        .home-button {
            position: absolute; /* Position it relative to the body */
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
        }

        .home-button:hover {
            background-color: #0056b3; /* Darker shade on hover */
        }
    </style>
</head>

<body>
    <a href="/" class="home-button">Home</a> <!-- Home button -->

    <div class="wrapper">
        <div class="title">Reset Password</div>

        <!-- Success and Error Messages -->
        <script>
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = "{{ route('login') }}"; // Redirect to login page
                });
            @endif

            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '{{ implode(', ', $errors->all()) }}',
                    confirmButtonText: 'OK'
                });
            @endif
        </script>

        <form method="POST" action="{{ route('password.reset') }}">
            @csrf
            <div class="field">
                <input type="password" name="password" placeholder="New Password" required>
            </div>
            <div class="field">
                <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
            </div>
            <div class="field btn">
                <input type="submit" value="Reset Password">
            </div>
        </form>
    </div>

    <script>
        // Display SweetAlert for OTP verified (if applicable)
        @if (session('otp_verified'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('otp_verified') }}',
                confirmButtonText: 'OK'
            });
        @endif
    </script>
</body>

</html>