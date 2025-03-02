<x-layout>
    <main class="bg-gray-200 p-5">
        <section class=" bg-white mx-auto max-w-7xl px-8 py-6 w-150 mt-4 sm:px-6 lg:px-8 shadow-xl">
            <form method="POST" action="{{ route('signup') }}">
                <div class="form-group">
                    <label for="userName">First Name</label>
                    <input type="text" class="form-control" id="userName">
                </div>
                <div class="form-group">
                    <label for="userEmail">Email</label>
                    <input type="email" class="form-control" id="userEmail" aria-describedby="emailHelp" placeholder="Enter email">
                    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                </div>
                <div class="form-group">
                    <label for="userPassword">Password</label>
                    <input type="password" class="form-control" id="userPassword" placeholder="Password">
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label" for="exampleCheck1">I'm not a bot</label>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Submit</button>
            </form>
        </section>
    </main>
</x-layout>
