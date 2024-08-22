@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-2xl mb-6">Register</h2>
    <form id="registerForm">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-sm font-semibold">Name</label>
            <input type="text" name="name" id="name" class="w-full p-2 border border-gray-300 rounded mt-2" required>
        </div>
        <div class="mb-4">
            <label for="email" class="block text-sm font-semibold">Email</label>
            <input type="email" name="email" id="email" class="w-full p-2 border border-gray-300 rounded mt-2" required>
        </div>
        <div class="mb-4">
            <label for="password" class="block text-sm font-semibold">Password</label>
            <input type="password" name="password" id="password" class="w-full p-2 border border-gray-300 rounded mt-2" required>
        </div>
        <div class="mb-4">
            <label for="password_confirmation" class="block text-sm font-semibold">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="w-full p-2 border border-gray-300 rounded mt-2" required>
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Register</button>
    </form>
</div>

<script>
    document.getElementById('registerForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent form from submitting the traditional way

        const formData = {
            name: document.getElementById('name').value,
            email: document.getElementById('email').value,
            password: document.getElementById('password').value,
            password_confirmation: document.getElementById('password_confirmation').value,
        };

        axios.post('/api/register', formData)
            .then(response => {
                alert('User registered successfully!');
                // You can redirect the user or perform another action here
                window.location.href = "{{ route('login') }}";  
            })
            .catch(error => {
                console.error('There was an error!', error);
                alert('Registration failed. Please try again.');
            });
    });
</script>
@endsection
