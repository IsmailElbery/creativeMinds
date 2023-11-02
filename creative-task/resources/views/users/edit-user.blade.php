<!-- resources/views/auth/register.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    @include('layouts.alert')
                    <div class="card-header">Edit user</div>

                    <div class="card-body">
                        <form method="POST" action="{{route('editUser',$user->id)}}">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" value="{{$user->name}}" id="name" name="name" required>
                            </div>

                            <div class="mb-3">
                                <label for="mobile" class="form-label">Mobile</label>
                                <input type="text" class="form-control" value="{{ $user->mobile }}" id="mobile" name="mobile" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" >
                            </div>


                            <button type="submit" class="btn btn-primary">Edit User</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
