$(document).ready(function() {
    $('#createTaskForm').on('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        // Validate title length and characters
        const title = $('#taskTitle').val();
        if (title.length > 50 || !/^[A-Za-z\s]+$/.test(title)) {
            let errorMessage = 'Title cannot exceed 50 characters.';
            if (!/^[A-Za-z\s]+$/.test(title)) {
                errorMessage = 'Title can only contain letters and spaces.';
            }
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: errorMessage,
                confirmButtonText: 'OK'
            });
            return;
        }

        // Collect form data
        const formData = $(this).serialize();

        $.ajax({
            url: $(this).attr('action'), // Get the form action URL
            type: 'POST',
            data: formData,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Task created successfully!',
                    confirmButtonText: 'OK'
                }).then(() => {
                    location.reload(); // Reload the page to see the new task
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: xhr.responseJSON.message || 'Something went wrong!',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
});


