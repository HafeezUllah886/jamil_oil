@extends('layout.app')
@section('content')
    <div class="row">
        <div class="col-6 mx-auto">
            <div class="card ">
                <div class="card-header d-flex justify-content-between">
                    <h5>Profile Settings</h5>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('profile.update') }}" method="post">
                        @csrf
                        <div class="form-group mt-2">
                            <label for="username">Username</label>
                            <input type="text" name="username" required value="{{ auth()->user()->username }}" id="username"
                                class="form-control">
                        </div>

                        <div class="form-group mt-2">
                            <label for="password">Password (Leave blank to keep current password)</label>
                            <input type="password" name="password" id="password" class="form-control" autocomplete="new-password">
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary w-100">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
