<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manage Users - Task Dashboard</title>

    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/manage-user.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

</head>

<body id="page-top">

    <div class="sidebar">
        <a class="sidebar-brand" href="{{ route('dashboard') }}">
            <div class="sidebar-brand-icon rotate-n-15">
                <i class="fas fa-user-circle"></i>
            </div>
            <img src="{{ asset('logo/tasktopia.png') }}" alt="Task Manager Logo" class="sidebar-brand-text" style="width: 200px; height: auto;">
        </a>
        <hr class="sidebar-divider">
        <div class="profile-info text-center" data-toggle="modal" data-target="#editProfileModal">
            <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('path/to/default/image.png') }}" 
                alt="Profile Picture" class="profile-pic rounded-circle" style="width: 70px; height: 70px;">
            <h6 style="font-size: 1.1rem; margin-top: 0.5rem;">{{ Auth::user()->name }}</h6>
        </div>
        <hr class="sidebar-divider">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="bi bi-house"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('tasks.create') }}">
                    <i class="bi bi-plus-circle"></i>
                    <span>Create Task</span>
                </a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('taskcompleted') }}">
                    <i class="bi bi-check-circle"></i>
                    <span>Completed Tasks</span>
                </a>
            </li>
            @if (Auth::user()->user_role == 1) 
            <li class="nav-item">
                <a class="nav-link" href="{{ route('manage.users') }}">
                    <i class="bi bi-person-check"></i> 
                    <span>Manage Users</span>
                </a>
            </li>
            @endif
            <li class="nav-item" style="margin-top: auto;">
                <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </div>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="mt-4">Manage Users</h1>

                <div class="d-flex">
                    <input type="text" id="searchEmail" class="form-control" placeholder="Search by Gmail" style="max-width: 250px;" onkeyup="searchUser()">
                    <button class="btn btn-success add-user-btn ml-3" data-toggle="modal" data-target="#addUserModal">
                        <i class="bi bi-person-plus"></i> Add User
                    </button>
                </div>
            </div>


                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($users as $index => $user)
                    <tr>
                        <td>{{ $users->firstItem() + $index }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->user_role == 1 ? 'Admin' : 'User' }}</td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-primary btn-sm edit-btn" data-toggle="modal" data-target="#editUserModal" 
                                        data-id="{{ $user->id }}" 
                                        data-name="{{ $user->name }}" 
                                        data-email="{{ $user->email }}" 
                                        data-role="{{ $user->user_role }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>

                <!-- Pagination Controls at the Bottom -->
                <div class="pagination-container">
                    <div class="pagination-left">
                        Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} entries
                    </div>
                    <div class="pagination-right">
                        <ul class="pagination">
                            @php
                                $currentPage = $users->currentPage();
                                $lastPage = $users->lastPage();
                                
                                // Define the range for page display
                                $startPage = max(1, $currentPage - 4); // Start 4 pages before
                                $endPage = min($lastPage, $currentPage + 4); // End 4 pages after

                                // Adjust the pagination to show only a max of 9 pages
                                $startPage = max(1, $startPage);
                                $endPage = min($lastPage, $endPage);
                            @endphp

                            <!-- First page link -->
                            @if($startPage > 1)
                                <li class="page-item">
                                    <a class="page-link" href="{{ $users->url(1) }}">1</a>
                                </li>
                                @if($startPage > 2)
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                @endif
                            @endif

                            <!-- Page links within the range -->
                            @for($i = $startPage; $i <= $endPage; $i++)
                                <li class="page-item {{ $currentPage == $i ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $users->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor

                            <!-- Last page link -->
                            @if($endPage < $lastPage)
                                @if($endPage < $lastPage - 1)
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                @endif
                                <li class="page-item">
                                    <a class="page-link" href="{{ $users->url($lastPage) }}">{{ $lastPage }}</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Adding User -->
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserLabel">Add User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addUserForm" method="POST" action="{{ route('users.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="addUserName">Name</label>
                            <input type="text" class="form-control" id="addUserName" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="addUserEmail">Email</label>
                            <input type="email" class="form-control" id="addUserEmail" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="addUserPassword">Password</label>
                            <input type="password" class="form-control" id="addUserPassword" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="addUserRole">Role</label>
                            <select class="form-control" id="addUserRole" name="user_role" required>
                                <option value="1">Admin</option>
                                <option value="0">User</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary update-user-btn">Add User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Editing User -->
    <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserLabel">Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm" method="POST" action="">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="userId" name="userId">
                        <div class="form-group">
                            <label for="userName">Name</label>
                            <input type="text" class="form-control" id="userName" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="userEmail">Email</label>
                            <input type="email" class="form-control" id="userEmail" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="userRole">Role</label>
                            <select class="form-control" id="userRole" name="user_role" required>
                                <option value="1">Admin</option>
                                <option value="0">User</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary update-user-btn">Update User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

        <!-- Modal for Editing Profile -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileLabel">Edit Profile</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editProfileForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="profileName">Name</label>
                            <input type="text" class="form-control" id="profileName" name="name" value="{{ Auth::user()->name }}" required>
                        </div>
                        <div class="form-group">
                            <label for="profilePicture">Profile Picture</label>
                            <input type="file" class="form-control-file" id="profilePicture" name="profile_picture">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary save-changes-btn" onclick="submitProfileForm()">Save Changes</button>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="{{ asset('js/editProfile.js') }}"></script> 

    <script>
    const profileUpdateUrl = "{{ route('profile.update') }}";
    </script>

    <script>
        $(document).ready(function() {
            $('.edit-btn').on('click', function() {
                const userId = $(this).data('id');
                const userName = $(this).data('name');
                const userEmail = $(this).data('email');
                const userRole = $(this).data('role');

                $('#userId').val(userId);
                $('#userName').val(userName);
                $('#userEmail').val(userEmail);
                $('#userRole').val(userRole);
                $('#editUserForm').attr('action', `/users/${userId}`);
            });
        });    
    </script>

    <script>
        // Handle form submission for adding a new user via AJAX
        $(document).ready(function () {
            $('#addUserForm').on('submit', function (e) {
                e.preventDefault(); // Prevent normal form submission

                // Get form data
                var formData = new FormData(this);

                // Make AJAX request
                $.ajax({
                    url: "{{ route('users.store') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        // Show success message if user is created
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.success,
                        }).then(function() {
                            window.location.reload(); // Reload the page
                        });
                    },
                    error: function (xhr) {
                        // Handle error, such as email already taken
                        if (xhr.status === 400) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: xhr.responseJSON.error, // Show the error message from response
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Email has already been taken!',
                            });
                        }
                    }
                });
            });
        });



        function searchUser() {
    var query = $('#searchEmail').val(); // Get the value from the search bar
    
    // Make an AJAX request to the backend to filter users by email
    $.ajax({
        url: "{{ route('users.search') }}",  // You will create this route in your web.php
        type: "GET",
        data: {
            email: query
        },
        success: function(response) {
            // Clear the current table and display the filtered users
            var tableBody = $('table tbody');
            tableBody.empty();
            
            response.users.forEach(function(user, index) {
                var row = `<tr>
                    <td>${index + 1}</td>
                    <td>${user.name}</td>
                    <td>${user.email}</td>
                    <td>${user.user_role == 1 ? 'Admin' : 'User'}</td>
                    <td>
                        <div class="btn-group">
                            <button class="btn btn-primary btn-sm edit-btn" data-toggle="modal" data-target="#editUserModal" 
                                data-id="${user.id}" 
                                data-name="${user.name}" 
                                data-email="${user.email}" 
                                data-role="${user.user_role}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="/users/${user.id}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>`;
                tableBody.append(row);
            });
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Something went wrong while searching.',
            });
        }
    });
}

    </script>


</body>
</html>
