@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tasks</h1>
    <div class="mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTaskModal">Create Task</button>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Status</th>
                <th>Due Date</th>
                <th>Assigned Users</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="tasksTableBody">
            @foreach($tasks as $task)
            <tr id="task-{{ $task->id }}">

                @can('update tasks')

                <td contenteditable="true" onblur="updateTaskField({{ $task->id }}, 'title', this.textContent)">{{ $task->title }}</td>
                <td contenteditable="true" onblur="updateTaskField({{ $task->id }}, 'description', this.textContent)">{{ $task->description }}</td>
                <td>
                    <select onchange="updateTaskField({{ $task->id }}, 'status', this.value)">
                        <option value="pending" {{ $task->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ $task->status === 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </td>
                <td>
                    <input type="date" value="{{ $task->due_date }}" onchange="updateTaskField({{ $task->id }}, 'due_date', this.value)">
                </td>
                @endcan


                @cannot('update tasks')
                    <td>{{ $task->title }}</td>
                    <td>{{ $task->description }}</td>
                    <td>{{ $task->status }}</td>
                    <td>{{ $task->due_date }}</td>
                @endcannot

                <td>
                    @foreach($task->users as $user)
                    <span class="badge bg-info text-dark">{{ $user->name }}</span>
                    @endforeach
                </td>
                <td>
                    @can('assign tasks')
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#assignUserModal" onclick="prepareAssignUsers({{ $task->id }})">Assign Users</button>
                    @endcan

                    @can('delete tasks')
                    <button class="btn btn-danger" onclick="deleteTask({{ $task->id }})">Delete</button>
                    @endcan
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Create Task Modal -->
    <div class="modal fade" id="createTaskModal" tabindex="-1" aria-labelledby="createTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="createTaskForm" action="{{ route('tasks.store') }}" method="POST">
                    @csrf

                    <input type="hidden" name="team_id" value="{{ $team->id }}" />
                    <div class="modal-header">
                        <h5 class="modal-title" id="createTaskModalLabel">Create Task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="due_date" class="form-label">Due Date</label>
                            <input type="date" class="form-control" id="due_date" name="due_date" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Assign Users Modal -->
    <div class="modal fade" id="assignUserModal" tabindex="-1" aria-labelledby="assignUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="assignUserForm">
                    @csrf
                    <input type="hidden" id="assignTaskId">
                    <div class="modal-header">
                        <h5 class="modal-title" id="assignUserModalLabel">Assign Users</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label for="users" class="form-label">Select Users</label>
                        <select class="form-select" id="users" name="users[]" multiple>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Assign</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function updateTaskField(taskId, field, value) {
    fetch(`/tasks/${taskId}`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ field, value }),
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            alert('Failed to update task.');
        }else{
            alert('Successfully updated task.');
        }
    });
}

function prepareAssignUsers(taskId) {
    document.getElementById('assignTaskId').value = taskId;
}

document.getElementById('assignUserForm').onsubmit = function(e) {
    e.preventDefault();
    const taskId = document.getElementById('assignTaskId').value;
    const formData = new FormData(this);

    fetch(`/tasks/${taskId}/assign-users`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const usersCell = document.querySelector(`#task-${taskId} td:nth-child(5)`);
            usersCell.innerHTML = '';
            data.users.forEach(user => {
                const badge = document.createElement('span');
                badge.className = 'badge bg-info text-dark';
                badge.textContent = user.name;
                usersCell.appendChild(badge);
            });
            $('#assignUserModal').modal('hide');
        }
    });
};

function deleteTask(taskId) {
    fetch(`/tasks/${taskId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById(`task-${taskId}`).remove();
            alert('Successfully deleted task.');
        }
    });
}
</script>
@endsection
