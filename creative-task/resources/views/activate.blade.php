<!-- resources/views/activate.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Activate Account</div>

                    <div class="card-body">
                    @include('layouts.alert')
                        <form method="POST" action="{{ route('activate') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="activation_code" class="form-label">Activation Code</label>
                                <input type="text" class="form-control" id="activation_code" name="activation_code" required>
                                <input  value="{{$mobile}}" class="form-control" id="mobile" name="mobile" hidden>
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="button" class="btn btn-secondary" id="resendButton">Resend</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add any JavaScript code if needed -->
    <script>
        // Example: JavaScript code to handle resend button click
        document.getElementById('resendButton').addEventListener('click', function() {
            // Implement logic to resend activation code
            alert('Activation code resent!');
        });
    </script>
@endsection
