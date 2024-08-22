@extends('layouts.app')

@section('content')
@if (auth()->check())
<div class="bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-2xl mb-6">Create New Post</h2>
    <form id="createPostForm">
        @csrf
        <div class="mb-4">
            <label for="title" class="block text-sm font-semibold">Title</label>
            <input type="text" name="title" id="title" class="w-full p-2 border border-gray-300 rounded mt-2" required>
        </div>
        <div class="mb-4">
            <label for="body" class="block text-sm font-semibold">Body</label>
            <textarea name="body" id="body" rows="5" class="w-full p-2 border border-gray-300 rounded mt-2" required></textarea>
        </div>
        <input type="hidden" id="userId" value="{{ auth()->user()->id }}">
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create Post</button>
    </form>
</div>

<script>
    document.getElementById('createPostForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = {
            title: document.getElementById('title').value,
            body: document.getElementById('body').value,
            user_id: document.getElementById('userId').value, // Include user ID here
        };

        axios.post('/api/posts', formData)
            .then(response => {
                alert('Post created successfully!');
                // Redirect to another page or clear the form, as needed.
                // window.location.href = '/posts'; // Example redirect
            })
            .catch(error => {
                console.error('There was an error!', error.response.data);
                alert('Failed to create the post. Please try again.');
            });
    });
</script>
@else
<script>
    // If the user is not authenticated, redirect to the login page
    window.location.href = "{{ route('login') }}";
</script>
@endif
@endsection
