const loginText = document.querySelector(".title-text .login");
const loginForm = document.querySelector("form.login");
const loginBtn = document.querySelector("label.login");
const signupBtn = document.querySelector("label.signup");
const signupLink = document.querySelector("form .signup-link a");

signupBtn.onclick = () => {
    loginForm.style.marginLeft = "-50%";
    loginText.style.marginLeft = "-50%";
};

loginBtn.onclick = () => {
    loginForm.style.marginLeft = "0%";
    loginText.style.marginLeft = "0%";
};

signupLink.onclick = () => {
    signupBtn.click();
    return false;
};

document.getElementById('otp-form').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission

    const formData = new FormData(this);

    // Show loading spinner while waiting for the server response
    Swal.fire({
        title: 'Verifying OTP...',
        text: 'Please wait a moment.',
        icon: 'info',
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading(); // Show loading spinner
        }
    });

    // Send OTP verification request via AJAX
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        Swal.close(); // Close the loading spinner

        if (data.status === 'success') {
            // Show success message with SweetAlert2
            Swal.fire({
                title: 'OTP Verified!',
                text: data.message, // Message from backend response
                icon: 'success',
                confirmButtonText: 'Proceed to Reset Password'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Close the OTP modal when user clicks "Proceed to Reset Password"
                    closeOtpModal();
                    // Show the Password Reset modal
                    showPasswordResetModal();
                }
            });
        } else {
            // Handle error: Show SweetAlert2 error message
            Swal.fire({
                title: 'Error!',
                text: data.message || 'An error occurred while verifying the OTP. Please try again.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    })
    .catch(error => {
        Swal.close(); // Close the loading spinner
        Swal.fire({
            title: 'Error!',
            text: 'An error occurred while verifying the OTP. Please try again.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    });
});