@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-lg">
    <form id="edit-post-form">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="title" class="block text-sm font-semibold">Title</label>
            <input type="text" id="title" name="title" value="{{ $post->title }}" class="w-full p-2 border border-gray-300 rounded mt-2" required>
        </div>
        <div class="mb-4">
            <label for="body" class="block text-sm font-semibold">Body</label>
            <textarea id="body" name="body" rows="5" class="w-full p-2 border border-gray-300 rounded mt-2" required>{{ $post->body }}</textarea>
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Post</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('edit-post-form');
        const postId = '{{ $post->id }}'; // Post ID from Blade template

        // Set CSRF Token for Axios requests
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const title = document.getElementById('title').value;
            const body = document.getElementById('body').value;

            axios.put(`/posts/${postId}`, { title, body })
                .then(response => {
                    alert('Post updated successfully!');
                    window.location.href = `/posts/${postId}`;
                })
                .catch(error => console.error('Error updating post:', error));
        });
    });
</script>
@endsection
