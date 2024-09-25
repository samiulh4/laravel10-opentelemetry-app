@extends('layouts.default')

@section('content')
<div class="container">
    <h1>Edit User</h1>

    <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" required>
        </div>

        <div class="form-group">
            <label for="profile_image">Profile Image:</label>
            @if ($user->profile_image)
            <div>
                <img src="{{ asset('storage/' . $user->profile_image) }}" alt="User Profile Image" width="150">
            </div>
            @endif
            <input type="file" name="profile_image" id="profile_image" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Update User</button>
    </form>
</div>
@endsection
