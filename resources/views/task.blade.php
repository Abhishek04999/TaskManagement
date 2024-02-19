<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h1 style="text-align:center;">Task Management System</h1>
        <input type="hidden" id="filterStatusHidden" name="status" value="">
        <input type="hidden" id="filterDateFromHidden" name="date_from" value="">
        <input type="hidden" id="filterDateToHidden" name="date_to" value="">
        <form id="filterForm" action="{{ route('tasks.sorting') }}" method="GET" class="row">
            <div class="form-group col-md-4">
                <label for="filterStatus">Status:</label>
                <select class="form-control form-control-sm" id="filterStatus" name="status">
                    <!-- Update filter options to include "All" -->
                    <option value="" {{ !$selectedStatus ? 'selected' : '' }}>All</option>
                    <option value="pending" {{ $selectedStatus == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="progress" {{ $selectedStatus == 'progress' ? 'selected' : '' }}>Progress</option>
                    <option value="completed" {{ $selectedStatus == 'completed' ? 'selected' : '' }}>Completed</option>

                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="filterDateFrom">From Date:</label>
                <input type="date" class="form-control form-control-sm" id="filterDateFrom" name="date_from"
                    value="{{ $selectedDateFrom }}" placeholder="From Date">
            </div>
            <div class="form-group col-md-4">
                <label for="filterDateTo">To Date:</label>
                <input type="date" class="form-control form-control-sm" id="filterDateTo" name="date_to"
                    value="{{ $selectedDateTo }}" placeholder="To Date">
            </div>
            <div class="form-group col-md-4">
                <label for="filterStatus">Category:</label>
                <select class="form-control form-control-sm" id="filterStatus" name="cat_name">
                    <!-- Update filter options to include "All" -->
                    <option value="" {{ !$selectedStatus ? 'selected' : '' }}>All</option>
                    @foreach ($category as $cat )

                    <option value="{{$cat->Cat_name}}" {{ $selectedCat == $cat->Cat_name ?'selected' : '' }}>{{$cat->Cat_name}}</option>
                    @endforeach
                </select>
            </div>
        </form>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#taskModal">Add Task</button>
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#categoryModal">Add
            Category</button>

        <form action="{{ route('logout') }}" method="post">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>


        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Deadline</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tasks as $task)
                    <tr>
                        <td>{{ $task->title }}</td>
                        <td>{{ $task->category }}</td>
                        <td>{{ $task->description }}</td>
                        <td>{{ $task->deadline }}</td>
                        <td>{{ $task->status }}</td>
                        <td>
                            <!-- Edit Button -->
                            <div>
                                <div>
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#editModal{{ $task->id }}">Edit</button>
                                </div>
                                <div>
                                    <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-info">View</a>
                                </div>
                                <div>
                                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this task?')">
                                        @csrf
                                        @method('DELETE')
                                        <!-- Add hidden inputs for filter values -->
                                        <input type="hidden" name="status" value="{{ $selectedStatus }}">
                                        <input type="hidden" name="date_from" value="{{ $selectedDateFrom }}">
                                        <input type="hidden" name="date_to" value="{{ $selectedDateTo }}">
                                        <input type="hidden" name="cat_name" value="{{ $selectedCat }}">
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>

                                </div>
                            </div>
                            <!-- Edit Task Modal -->
                            <div class="modal fade" id="editModal{{ $task->id }}" tabindex="-1" role="dialog"
                                aria-labelledby="editModalLabel{{ $task->id }}" aria-hidden="true"
                                data-backdrop="static" data-keyboard="false">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel{{ $task->id }}">Edit Task
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="form-group">
                                                    <label for="editTitle{{ $task->id }}">Title</label>
                                                    <input type="text" class="form-control"
                                                        id="editTitle{{ $task->id }}" name="title"
                                                        value="{{ $task->title }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="editCategory{{ $task->id }}">Category</label>
                                                    <select class="form-control" id="editCategory{{ $task->id }}"
                                                        name="category_name" required>
                                                       @foreach ($category as $cat )
                                                       <option value="{{ $cat->Cat_name }}" {{ $cat->Cat_name == $task->category ? 'selected' : '' }}>
                                                        {{ $cat->Cat_name }}
                                                    </option>
                                                       @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label
                                                        for="editDescription{{ $task->id }}">Description</label>
                                                    <textarea class="form-control" id="editDescription{{ $task->id }}" name="description" rows="3" required>{{ $task->description }}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="editDeadline{{ $task->id }}">Deadline</label>
                                                    <input type="datetime-local" class="form-control"
                                                        id="editDeadline{{ $task->id }}" name="deadline"
                                                        value="{{ $task->deadline }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="editStatus{{ $task->id }}">Status</label>
                                                    <select class="form-control" id="editStatus{{ $task->id }}"
                                                        name="status" required>
                                                        <option value="pending"
                                                            {{ $task->status == 'pending' ? 'selected' : '' }}>Pending
                                                        </option>
                                                        <option value="progress"
                                                            {{ $task->status == 'progress' ? 'selected' : '' }}>
                                                            Progress
                                                        </option>
                                                        <option value="completed"
                                                            {{ $task->status == 'completed' ? 'selected' : '' }}>
                                                            Completed</option>
                                                    </select>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>


    </div>

    <!-- Task Modal -->
    <div class="modal fade" id="taskModal" tabindex="-1" role="dialog" aria-labelledby="taskModalLabel"
        aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="taskModalLabel">Add Task</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('tasks.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="category">Category</label>
                            <select class="form-control" id="category" name="category_name" required>
                                <option value=""></option>
                                @foreach ($category as $category)
                                    <option value="{{ $category->Cat_name }}">{{ $category->Cat_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="deadline">Deadline</label>
                            <input type="datetime-local" class="form-control" id="deadline" name="deadline"  required>
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="pending">Pending</option>
                                <option value="progress">Progress</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
    {{-- category modal --}}
    <div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="categoryModalLabel"
        aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="categoryModalLabel">Add Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('categories.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="categoryName">Category Name</label>
                            <input type="text" class="form-control" id="categoryName" name="categoryName"
                                required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Category</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Add this script after including jQuery -->
    <script>
        $(document).ready(function() {
            // Attach an event listener to all filter inputs
            $('#filterStatus, #filterDateFrom, #filterDateTo').on('change', function() {
                // Update hidden input values
                $('#filterStatusHidden').val($('#filterStatus').val());
                $('#filterDateFromHidden').val($('#filterDateFrom').val());
                $('#filterDateToHidden').val($('#filterDateTo').val());

                // Trigger form submission
                $('form#filterForm').submit();
            });
        });
    </script>


</body>

</html>
