@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Manage Roles and Permissions</h1>

    <!-- Display Users and Their Roles -->
    <div class="mb-5">
        <h3>Users and Roles</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Roles</th>
                    {{-- <th>Action</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span id="roles-{{ $user->id }}">
                            {{ $user->roles->pluck('name')->join(', ') }}
                        </span>
                    </td>
                    {{-- <td>
                        <button class="btn btn-sm btn-primary edit-role-btn" data-user-id="{{ $user->id }}">
                            Edit Role
                        </button>
                    </td> --}}
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Assign Role to User -->
    <div class="mb-3">
        <h3>Assign Role to User</h3>
        <form action="{{ route('roles-permissions.assign-role') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="user_id" class="form-label">User</label>
                <select class="form-select" id="user_id" name="user_id">
                    @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-select" id="roles" name="roles[]" multiple>
                    @foreach($roles as $role)
                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Assign Role</button>
        </form>
    </div>

    <!-- Assign Permission to Role -->
    <div class="mb-3">
        <h3>Assign Permission to Role</h3>
        <form id="assignPermissionForm" >
            @csrf
            <div class="mb-3">
                <label for="permission_role" class="form-label">Role</label>
                <select class="form-select" id="permission_role" name="role">
                    @foreach($roles as $role)
                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="permission" class="form-label">Permission</label>
                <select class="form-select" id="permission" name="permission">
                    @foreach($permissions as $permission)
                    <option value="{{ $permission->name }}">{{ $permission->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Assign Permission</button>
        </form>
    </div>
</div>

<!-- Modal for Editing Roles -->
<div class="modal fade" id="editRoleModal" tabindex="-1" role="dialog" aria-labelledby="editRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRoleModalLabel">Edit User Roles</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editRoleForm">
                    @csrf
                    <input type="hidden" id="editUserId" name="user_id">
                    <div class="form-group">
                        <label for="roles">Roles</label>
                        <select class="form-control" id="roles" name="roles[]" multiple>
                            @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="saveRolesBtn" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Edit Role Modal Trigger
        $('.edit-role-btn').click(function() {
            let userId = $(this).data('user-id');
            $('#editUserId').val(userId);

            let roles = $('#roles-' + userId).text().split(', ');
            $('#edit-roles').val(roles).trigger('change');

            var myModal = new bootstrap.Modal(document.getElementById('editRoleModal'));
            myModal.show();
        });

        // Save Changes for Roles
        $('#editRoleForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: '{{ route('roles-permissions.assign-role') }}',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    alert(response.message);
                    $('#editRoleModal').modal('hide');
                    location.reload(); // Refresh the page to update roles
                },
                error: function(xhr) {
                    alert(xhr.responseJSON.message || 'An error occurred');
                }
            });
        });

        // Assign Role to User
        $('#assignRoleForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: '{{ route('roles-permissions.assign-role') }}',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    alert(response.message);
                    $('#assignRoleForm')[0].reset();
                    location.reload(); // Refresh the page to update roles
                },
                error: function(xhr) {
                    alert(xhr.responseJSON.message || 'An error occurred');
                }
            });
        });

        // Assign Permission to Role
        $('#assignPermissionForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: '{{ route('roles-permissions.assign-permission') }}',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    alert(response.message);
                    $('#assignPermissionForm')[0].reset();
                },
                error: function(xhr) {
                    alert(xhr.responseJSON.message || 'An error occurred');
                }
            });
        });
    });
</script>
@endsection
