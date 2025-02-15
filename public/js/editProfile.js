function submitProfileForm() {
    const formData = new FormData(document.getElementById('editProfileForm'));

    fetch(profileUpdateUrl, { // Use the variable here
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