@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-2xl mb-6">Login</h2>
    <form id="loginForm">
        @csrf
        <div class="mb-4">
            <label for="email" class="block text-sm font-semibold">Email</label>
            <input type="email" name="email" id="email" class="w-full p-2 border border-gray-300 rounded mt-2" required>
        </div>
        <div class="mb-4">
            <label for="password" class="block text-sm font-semibold">Password</label>
            <input type="password" name="password" id="password" class="w-full p-2 border border-gray-300 rounded mt-2" required>
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Login</button>
    </form>
</div>

<script>
    document.getElementById('loginForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent form from submitting the traditional way

        const formData = {
            email: document.getElementById('email').value,
            password: document.getElementById('password').value,
        };

        axios.post('/api/login', formData)
            .then(response => {
                alert('Login successful!');
                // Handle successful login, e.g., redirect to a dashboard
                window.location.href = '/posts'; // Example redirect
            })
            .catch(error => {
                console.error('There was an error!', error.response.data);
                alert('Login failed. Please check your credentials and try again.');
            });
    });
</script>
@endsection

