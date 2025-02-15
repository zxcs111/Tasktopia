function submitProfileForm() {
    const formData = new FormData(document.getElementById('editProfileForm'));

    fetch('{{ route("profile.update") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok.');
        }
        return response.json();
    })
    .then(data => {
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Profile updated successfully!',
            confirmButtonText: 'OK'
        }).then(() => {
            location.reload(); 
        });
    })
    .catch(error => {
        console.error('There was a problem with the fetch operation:', error);
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Error updating profile. Please try again.',
            confirmButtonText: 'OK'
        });
    });
}

function confirmDelete(button) {
const form = button.closest('form'); // Get the parent form

Swal.fire({
title: 'Are you sure?',
text: "You won't be able to revert this!",
icon: 'warning',
showCancelButton: true,
confirmButtonColor: '#d33',
cancelButtonColor: '#3085d6',
confirmButtonText: 'Yes, delete it!',
cancelButtonText: 'No, keep it'
}).then((result) => {
if (result.isConfirmed) {
    // Submit the form via AJAX
    $.ajax({
        url: form.action,
        type: 'POST',
        data: $(form).serialize(),
        success: function(response) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: response.message,
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = response.redirect; // Redirect to the appropriate page
            });
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'An error occurred while deleting the task.',
                confirmButtonText: 'OK'
            });
        }
    });
}
});
}