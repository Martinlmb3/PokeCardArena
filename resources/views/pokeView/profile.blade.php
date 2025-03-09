@php
    use Illuminate\Support\Facades\Auth;
@endphp

<x-layout>
    <main class="bg-gray-200 p-5">
        <section class="flex flex-col my-8 mx-auto max-w-4xl bg-white shadow-xl p-4">
            <h1 class="text-center text-2xl font-bold mb-4">My Profile</h1>
            <form method="POST" action="{{ route('profile.update', ['id' => Auth::id()]) }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $name }}">
                </div>
                <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $email }}" aria-describedby="emailHelp">
                <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                </div>
                <div class="mb-3">
                <label for="password" class="form-label">New password</label>
                <input type="password" class="form-control" id="password" name="password">
                <small class="form-text text-muted">Leave blank to keep current password</small>
                </div>
                <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm password</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                </div>
                <div class="input-group mb-3">
                <input type="file" class="form-control" id="profile_image" name="profile_image" aria-describedby="inputGroupFileAddon03" aria-label="Upload">
                </div>
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>
        </section>
    </main>
</x-layout>
