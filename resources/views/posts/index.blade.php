@extends('layouts.app')

@section('content')
@if (auth()->check())
<div class="container">
    <h1 class="mb-4">Posts</h1>
    <div class="mb-4">
            <a href="{{ route('posts-create') }}" class="btn btn-primary">Create New Post</a>
        </div>
    <div id="posts-list" class="card-columns">
        <!-- Posts will be dynamically inserted here -->
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        axios.get('/api/poststest')
            .then(response => {
                const posts = response.data;
                let postsHtml = '';

                posts.forEach(post => {
                    postsHtml += `
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">${post.title}</h5>
                                <h6 class="card-subtitle mb-2 text-muted">By ${post.user.name} on ${new Date(post.created_at).toLocaleDateString()}</h6>
                                <p class="card-text">${post.body.substring(0, 15000)}</p>
                                <a href="/posts/${post.id}" class="card-link text-primary">Click Here For Comment</a>
                            </div>
                        </div>
                    `;
                });

                document.getElementById('posts-list').innerHTML = postsHtml;
            })
            .catch(error => {
                console.error("There was an error fetching the posts:", error);
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
