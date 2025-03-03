<x-layout>
    <main class="bg-gray-200 p-5">
        <section class="flex flex-col my-8 mx-auto max-w-4xl bg-white shadow-xl p-4">
            <h1 class="text-center text-2xl font-bold mb-4">My Profile</h1>
            <form>
                <div class="mb-3">
                <label for="firstName" class="form-label">First Name</label>
                <input type="text" class="form-control" id="firstName">
                </div>
                <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Email address</label>
                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                </div>
                <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">New password</label>
                <input type="password" class="form-control" id="exampleInputPassword1">
                </div>
                <div class="mb-3">
                <label for="exampleInputPassword2" class="form-label">Confirm password</label>
                <input type="password" class="form-control" id="exampleInputPassword2">
                </div>
                <div class="input-group mb-3">
                <input type="file" class="form-control" id="inputGroupFile03" aria-describedby="inputGroupFileAddon03" aria-label="Upload">
                </div>
                <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                <label class="form-check-label" for="exampleCheck1">Check me out</label>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </section>
    </main>
</x-layout>
