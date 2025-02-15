$(document).ready(function() {
    // Handle click event for edit button
    $('.edit-btn').on('click', function() {
        $('#taskId').val($(this).data('id'));
        $('#taskTitle').val($(this).data('title'));
        $('#taskDescription').val($(this).data('description'));
        $('#taskStatus').val($(this).data('status'));
        $('#taskDueDate').val($(this).data('due_date'));
        $('#taskPriority').val($(this).data('priority'));
    });

    // Handle form submission for editing the task
    $('#editTaskForm').on('submit', function(event) {
        event.preventDefault();
        
        const status = $('#taskStatus').val();
        
        if (status === 'Completed') {
            Swal.fire({
                title: 'Are you sure?',
                text: "You will be able to see this task in the Completed Tasks!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, complete it!',
                cancelButtonText: 'No, not yet'
            }).then((result) => {
                if (result.isConfirmed) {
                    const taskId = $('#taskId').val();
                    const formData = $(this).serialize();

                    $.ajax({
                        url: `/tasks/${taskId}`,
                        type: 'PUT',
                        data: formData,
                        success: function(response) {
                            $('#editTaskModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Task marked as completed!',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                $(`#task-${taskId}`).fadeOut();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'An error occurred while updating the task.',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        } else {
            const taskId = $('#taskId').val();
            const formData = $(this).serialize();

            $.ajax({
                url: `/tasks/${taskId}`,
                type: 'PUT',
                data: formData,
                success: function(response) {
                    $('#editTaskModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Task updated successfully!',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'An error occurred while updating the task.',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    });
});