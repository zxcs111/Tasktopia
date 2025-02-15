<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Task Dashboard</title>

    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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
            <li class="nav-item active">
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
            <div class="row priority-row">
                <div class="col-low">
                    <div class="priority-label priority-low">LOW</div>
                    @foreach ($tasks as $task)
                        @if ($task->priority == 'Low')
                        <div class="card shadow mb-3 task-card low" id="task-{{ $task->id }}"> 
                            <div class="card-body">
                                <strong>Title:</strong> {{ $task->title }}<br>
                                <strong>Description:</strong> 
                                <div class="description">{{ $task->description }}</div>
                                <strong>Due Date:</strong> {{ $task->due_date }}<br>
                                <strong>Status:</strong> 
                                <span class="
                                    @if($task->status == 'Pending') badge badge-warning 
                                    @elseif($task->status == 'In Progress') badge badge-info 
                                    @elseif($task->status == 'Completed') badge badge-success 
                                    @endif">
                                    {{ $task->status }}
                                </span><br>
                                <div class="mt-2">
                                    <button class="btn edit-btn" 
                                            data-id="{{ $task->id }}" 
                                            data-title="{{ $task->title }}" 
                                            data-description="{{ $task->description }}" 
                                            data-status="{{ $task->status }}" 
                                            data-due_date="{{ $task->due_date }}" 
                                            data-priority="{{ $task->priority }}" 
                                            data-toggle="modal" data-target="#editTaskModal">
                                        <i class="bi bi-pencil-fill"></i> Edit
                                    </button>
                                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display: inline;" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="redirect_to" value="dashboard">
                                        <button type="button" class="btn btn-danger delete-btn" onclick="confirmDelete(this)">
                                            <i class="bi bi-trash-fill"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>

                <div class="col-medium">
                    <div class="priority-label priority-medium">MEDIUM</div>
                    @foreach ($tasks as $task)
                        @if ($task->priority == 'Medium')
                        <div class="card shadow mb-3 task-card medium" id="task-{{ $task->id }}"> 
                            <div class="card-body">
                                <strong>Title:</strong> {{ $task->title }}<br>
                                <strong>Description:</strong> 
                                <div class="description">{{ $task->description }}</div>
                                <strong>Due Date:</strong> {{ $task->due_date }}<br>
                                <strong>Status:</strong> 
                                <span class="
                                    @if($task->status == 'Pending') badge badge-warning 
                                    @elseif($task->status == 'In Progress') badge badge-info 
                                    @elseif($task->status == 'Completed') badge badge-success 
                                    @endif">
                                    {{ $task->status }}
                                </span><br>
                                <div class="mt-2">
                                    <button class="btn edit-btn" 
                                            data-id="{{ $task->id }}" 
                                            data-title="{{ $task->title }}" 
                                            data-description="{{ $task->description }}" 
                                            data-status="{{ $task->status }}" 
                                            data-due_date="{{ $task->due_date }}" 
                                            data-priority="{{ $task->priority }}" 
                                            data-toggle="modal" data-target="#editTaskModal">
                                        <i class="bi bi-pencil-fill"></i> Edit
                                    </button>
                                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display: inline;" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="redirect_to" value="dashboard">
                                        <button type="button" class="btn btn-danger delete-btn" onclick="confirmDelete(this)">
                                            <i class="bi bi-trash-fill"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>

                <div class="col-high">
                    <div class="priority-label priority-high">HIGH</div>
                    @foreach ($tasks as $task)
                        @if ($task->priority == 'High')
                        <div class="card shadow mb-3 task-card high" id="task-{{ $task->id }}"> 
                            <div class="card-body">
                                <strong>Title:</strong> {{ $task->title }}<br>
                                <strong>Description:</strong> 
                                <div class="description">{{ $task->description }}</div>
                                <strong>Due Date:</strong> {{ $task->due_date }}<br>
                                <strong>Status:</strong> 
                                <span class="
                                    @if($task->status == 'Pending') badge badge-warning 
                                    @elseif($task->status == 'In Progress') badge badge-info 
                                    @elseif($task->status == 'Completed') badge badge-success 
                                    @endif">
                                    {{ $task->status }}
                                </span><br>
                                <div class="mt-2">
                                    <button class="btn edit-btn" 
                                            data-id="{{ $task->id }}" 
                                            data-title="{{ $task->title }}" 
                                            data-description="{{ $task->description }}" 
                                            data-status="{{ $task->status }}" 
                                            data-due_date="{{ $task->due_date }}" 
                                            data-priority="{{ $task->priority }}" 
                                            data-toggle="modal" data-target="#editTaskModal">
                                        <i class="bi bi-pencil-fill"></i> Edit
                                    </button>
                                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display: inline;" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="redirect_to" value="dashboard">
                                        <button type="button" class="btn btn-danger delete-btn" onclick="confirmDelete(this)">
                                            <i class="bi bi-trash-fill"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Editing Tasks -->
    <div class="modal fade" id="editTaskModal" tabindex="-1" role="dialog" aria-labelledby="editTaskLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTaskLabel">Edit Task</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editTaskForm" method="POST" action="">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="taskId" name="taskId">
                        <div class="mb-3">
                            <label for="taskTitle" class="form-label">Task Title</label>
                            <input type="text" class="form-control" id="taskTitle" name="title" required maxlength="50">
                        </div>
                        <div class="form-group">
                            <label for="taskDescription">Description</label>
                            <textarea class="form-control" id="taskDescription" name="description" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="taskStatus">Status</label>
                            <select class="form-control" id="taskStatus" name="status" required>
                                <option value="Pending">Pending</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="taskDueDate">Due Date</label>
                            <input type="date" class="form-control" id="taskDueDate" name="due_date" required>
                        </div>
                        <div class="form-group">
                            <label for="taskPriority">Priority</label>
                            <select class="form-control" id="taskPriority" name="priority" required>
                                <option value="Low">Low</option>
                                <option value="Medium">Medium</option>
                                <option value="High">High</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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
                    <button type="button" class="btn btn-primary" onclick="submitProfileForm()">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="{{ asset('js/deleteButton.js') }}"></script>
    <script src="{{ asset('js/editButton.js') }}"></script>
    <script src="{{ asset('js/editProfile.js') }}"></script> 

    <script>
    const profileUpdateUrl = "{{ route('profile.update') }}";
    </script>

</body>
</html>