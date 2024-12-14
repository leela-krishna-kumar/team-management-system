@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Team Management</h1>

    @can('create teams')
        <button id="createTeamBtn" class="btn btn-primary mb-3">Create New Team</button>
    @endcan

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="teamTableBody">
            @foreach($teams as $team)
            <tr id="teamRow-{{ $team->id }}">

                @can('update teams')
                    <td contenteditable="true" class="editable" data-id="{{ $team->id }}" data-field="name">{{ $team->name }}</td>
                    <td contenteditable="true" class="editable" data-id="{{ $team->id }}" data-field="description">{{ $team->description }}</td>
                @endcan

                @cannot('update teams')
                    <td>{{ $team->name }}</td>
                    <td>{{ $team->description }}</td>
                @endcannot

                <td>

                    @can('view tasks')
                        <a class="btn btn-info" href="{{ url('teams/'. $team->id . '/tasks') }}">Tasks</a>
                    @endcan

                    @can('delete teams')
                        <button class="btn btn-danger deleteTeamBtn" data-id="{{ $team->id }}">Delete</button>
                    @endcan

                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    $(document).on('click', '#createTeamBtn', function () {
        let name = prompt('Enter Team Name:');
        let description = prompt('Enter Team Description:');
        if (name) {
            $.post('/teams', {
                name: name,
                description: description,
                _token: '{{ csrf_token() }}'
            }).done(function (response) {
                $('#teamTableBody').append(`
                <tr id="teamRow-${response.id}">
                    <td contenteditable="true" class="editable" data-id="${response.id}" data-field="name">${response.name}</td>
                    <td contenteditable="true" class="editable" data-id="${response.id}" data-field="description">${response.description}</td>
                    <td><a class="btn btn-info" href="/teams/${response.id}/tasks">Tasks</a>
                    <button class="btn btn-danger deleteTeamBtn" data-id="${response.id}">Delete</button></td>
                </tr>
                `);
            }).fail(function (xhr) {
                alert('Error: ' + xhr.responseJSON.message);
            });
        }
    });

    $(document).on('blur', '.editable', function () {
        let id = $(this).data('id');
        let field = $(this).data('field');
        let value = $(this).text();
        $.ajax({
            url: `/teams/${id}`,
            method: 'PATCH',
            data: {
                [field]: value,
                _token: '{{ csrf_token() }}'
            }
        }).done(function () {
            alert('Updated successfully!');
        }).fail(function (xhr) {
            // alert('Error: ' + xhr.responseJSON.message);
        });
    });

    $(document).on('click', '.deleteTeamBtn', function () {
        let id = $(this).data('id');
        if (confirm('Are you sure you want to delete this team?')) {
            $.ajax({
                url: `/teams/${id}`,
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}' }
            }).done(function () {
                $(`#teamRow-${id}`).remove();
            }).fail(function (xhr) {
                alert('Error: ' + xhr.responseJSON.message);
            });
        }
    });
</script>
@endsection
