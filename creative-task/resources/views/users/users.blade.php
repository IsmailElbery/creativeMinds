<!-- resources/views/users.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">Users</div>
                    @include('layouts.alert')

                    <div class="card-body">
                        <div class="mb-3">
                            <a href="{{ route('addUser') }}" class="btn btn-primary">Add User</a>
                        </div>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Active</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Add rows of user data dynamically -->
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->mobile }}</td>
                                        <td>{{ $user->active ? 'Yes' : 'No' }}</td>
                                        <td>
                                            <a href="{{ route('editUser', $user->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                            <a href="{{ route('deleteUser', $user->id) }}" class="btn btn-sm btn-danger">Delete</a>
                                            @if(!$user->active)
                                            <a href="{{ route('activateUser', $user->id) }}" class="btn btn-sm btn-success">Activate</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                <!-- Add more rows as needed -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
