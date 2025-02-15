<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - TaskTopia</title>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/landingpage.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.13/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.13/dist/sweetalert2.min.js"></script>
    <style>
        * {
            box-sizing: border-box; /* Ensure padding and borders are included in total width/height */
        }

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
            width: 300px; /* Fixed width for the form */
            text-align: center; /* Center text inside the wrapper */
        }

        .title-text {
            margin-bottom: 20px;
        }

        .field {
            margin-bottom: 15px;
        }

        .field input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
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
            width: 100%; /* Full-width button */
        }

        .field.btn input[type="submit"]:hover {
            background-color: #218838; 
        }

        .alert {
            text-align: center;
            margin-bottom: 15px;
        }

        .home-button {
            position: fixed; /* Change to fixed positioning */
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
    </style>
</head>

<body>
    <a href="/" class="home-button">Home</a> <!-- Home button -->

    <div class="wrapper">
        <div class="title-text">
            <div class="title">Forgot Password</div>
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
            <form id="forgot-password-form" method="POST" action="{{ route('password.sendOtp') }}" onsubmit="sendOtp(event)">
                @csrf
                <div class="field">
                    <input type="email" name="email" id="email" placeholder="Enter your email" required>
                </div>
                <div class="field btn">
                    <input type="submit" value="Send OTP">
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function sendOtp(event) {
            event.preventDefault();

            var email = document.getElementById('email').value;

            if (email === "") {
                alert('Please enter a valid email address.');
                return;
            }

            Swal.fire({
                title: 'Sending OTP...',
                text: 'Please wait a moment.',
                icon: 'info',
                showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: '/password/send-otp',
                method: 'POST',
                data: {
                    email: email,
                    _token: document.querySelector('[name="csrf-token"]').content
                },
                success: function (response) {
                    Swal.close();

                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'OTP has been sent successfully! Please check your email.',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // Redirect to the OTP input page after the success message is acknowledged
                        window.location.href = '/verify-otp'; // Change this to your OTP handling page
                    });
                },
                error: function (xhr) {
                    Swal.close();
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: xhr.responseJSON.message
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong! Please try again.'
                        });
                    }
                }
            });
        }
    </script>
</body>

</html>