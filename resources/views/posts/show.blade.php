@extends('layouts.app')

@section('content')
<!-- Display Post Details -->
<div id="post-details" class="bg-white p-6 rounded-lg shadow-lg mb-6">
    <!-- Post content will be dynamically inserted here -->
    <h2 class="text-3xl font-bold">{{ $post->title }}</h2>
    <p class="text-gray-600">By {{ $post->user->name }} on {{ $post->created_at->format('F j, Y') }}</p>
    <p class="mt-4">{{ $post->body }}</p>
    @if(auth()->id() == $post->user_id)
    <div class="mt-4">
        <a href="/posts/{{ $post->id }}/edit" class="text-blue-500">Edit</a>
        <button type="button" class="text-red-500 ml-4" id="delete-post-button">Delete</button>
    </div>
    @endif
</div>

<!-- Store post ID and authenticated user ID in data attributes -->

<div id="post-data" data-post-id="{{ $post->id }}" data-auth-user-id="{{ auth()->id() }}"></div>

<!-- Display Comments -->
<div id="comments-section" class="bg-white p-6 rounded-lg shadow-lg mb-6">
    <h3 class="text-2xl font-semibold">Comments</h3>
    <!-- Comments will be dynamically inserted here -->
</div>

<!-- Add a New Comment Form -->
@if(auth()->check())
<div class="bg-white p-6 rounded-lg shadow-lg">
    <form id="comment-form">
        @csrf
        <div class="mb-4">
            <label for="comment" class="block text-sm font-semibold">Add a comment</label>
            <textarea name="comment" id="comment" rows="3" class="w-full p-2 border border-gray-300 rounded mt-2" required></textarea>
            <input type="hidden" id="userId" value="{{ auth()->user()->id }}">
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Comment</button>
    </form>
</div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const postElement = document.getElementById('post-data');
        const postId = postElement.dataset.postId;
        const authUserId = postElement.dataset.authUserId;
        const postDetailsElement = document.getElementById('post-details');
        const commentsSection = document.getElementById('comments-section');

        // Set CSRF Token for Axios requests
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Fetch and display post details
        function fetchPostDetails() {
            axios.get(`/api/posts/${postId}`)
                .then(response => {
                    const post = response.data;
                    let postHtml = `
                        <h2 class="text-3xl font-bold">${post.title}</h2>
                        <p class="text-gray-600">By ${post.user.name} on ${new Date(post.created_at).toLocaleDateString()}</p>
                        <p class="mt-4">${post.body}</p>
                    `;
                    if (authUserId === post.user_id.toString()) {
                        postHtml += `
                            <div class="mt-4">
                                <a href="/posts/${post.id}/edit" class="text-blue-500">Edit</a>
                                <button type="button" class="text-red-500 ml-4" id="delete-post-button">Delete</button>
                            </div>
                        `;
                    }
                    postDetailsElement.innerHTML = postHtml;
                })
                .catch(error => console.error('Error fetching post details:', error));
        }

        // Fetch and display comments
        function fetchComments() {
            axios.get(`/api/posts/${postId}/comments`)
                .then(response => {
                    const comments = response.data;
                    let commentsHtml = '';
                    comments.forEach(comment => {
                        const userName = comment.user?.name || 'Anonymous';
                        const likesCount = comment.likes_count ?? 0;

                        commentsHtml += `
                    <div class="mt-4">
                        <p><strong>${userName}</strong> said:</p>
                        <p>${comment.comment}</p>
                        <div class="mt-2 flex items-center">
                            <!-- Like Button -->
                            <button type="button" class="text-blue-500 like-button" data-id="${comment.id}">
                                Like
                            </button>
                            <!-- Unlike Button -->
                            <button type="button" class="text-red-500 unlike-button ml-4" data-id="${comment.id}">
                                Unlike
                            </button>
                            <!-- Likes Count -->
                            <span class="ml-2">${likesCount} likes</span>
                        </div>
                    </div>
                `;
                    });
                    commentsSection.innerHTML = commentsHtml;
                })
                .catch(error => console.error('Error fetching comments:', error));
        }


        document.addEventListener('DOMContentLoaded', function() {
            // Get post ID and user ID from data attributes
            const postElement = document.getElementById('post-data');
            const postId = postElement.dataset.postId;
            const userId = postElement.dataset.authUserId;

            // Print to console
            console.log('Post ID:', postId);
            console.log('User ID:', userId);
        });

        // Handle new comment form submission
        document.getElementById('comment-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const commentText = document.getElementById('comment').value;
            const userId = document.getElementById('userId').value;
            const postId = document.getElementById('post-data').dataset.postId;

            const formData = {
                comment: commentText,
                user_id: userId,
                post_id: postId
            };

            console.log('Form Data:', formData); // Add this line to check the data

            axios.post(`/api/posts/${postId}/comments`, formData)
                .then(response => {
                    console.log('Response:', response.data); // Check the response
                    fetchComments(); // Refresh comments
                    document.getElementById('comment-form').reset(); // Reset form
                })
                .catch(error => console.error('Error adding comment:', error));
        });


        // Handle like/unlike button clicks
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('like-button')) {
                const commentId = event.target.getAttribute('data-id');

                axios.post(`/api/comments/${commentId}/like`)
                    .then(response => {
                        fetchComments(); // Refresh the comments to update the likes count in the UI
                    })
                    .catch(error => console.error('Error liking comment:', error));
            }

            if (event.target.classList.contains('unlike-button')) {
                const commentId = event.target.getAttribute('data-id');

                axios.post(`/api/comments/${commentId}/unlike`)
                    .then(response => {
                        fetchComments(); // Refresh the comments to update the likes count in the UI
                    })
                    .catch(error => console.error('Error unliking comment:', error));
            }
        });

        // Function to fetch and display comments, including like/unlike buttons and the like count
        function fetchComments() {
            axios.get(`/api/posts/${postId}/comments`)
                .then(response => {
                    const comments = response.data;
                    let commentsHtml = '';
                    comments.forEach(comment => {
                        const userName = comment.user?.name || 'Anonymous';
                        const likesCount = comment.likes_count ?? 0; // Default to 0 if undefined

                        commentsHtml += `
                    <div class="mt-4">
                        <p><strong>${userName}</strong> said:</p>
                        <p>${comment.comment}</p>
                        <div class="mt-2 flex items-center">
                            <button type="button" class="text-blue-500 like-button" data-id="${comment.id}">
                                Like
                            </button>
                            <button type="button" class="text-red-500 unlike-button ml-2" data-id="${comment.id}">
                                Unlike
                            </button>
                            <span class="ml-2">${likesCount} likes</span>
                        </div>
                    </div>
                `;
                    });
                    commentsSection.innerHTML = commentsHtml;
                })
                .catch(error => console.error('Error fetching comments:', error));
        }

        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('like-button')) {
                const commentId = event.target.getAttribute('data-id');

                axios.post(`/api/comments/${commentId}/like`)
                    .then(response => {
                        fetchComments(); // Refresh the comments to update the UI
                    })
                    .catch(error => console.error('Error liking comment:', error));
            }

            if (event.target.classList.contains('unlike-button')) {
                const commentId = event.target.getAttribute('data-id');

                axios.post(`/api/comments/${commentId}/unlike`)
                    .then(response => {
                        fetchComments(); // Refresh the comments to update the UI
                    })
                    .catch(error => console.error('Error unliking comment:', error));
            }
        });





        // Handle post deletion
        postDetailsElement.addEventListener('click', function(e) {
            if (e.target.id === 'delete-post-button') {
                if (confirm('Are you sure you want to delete this post?')) {
                    axios.delete(`/api/posts/${postId}`)
                        .then(() => {
                            window.location.href = '/';
                        })
                        .catch(error => console.error('Error deleting post:', error));
                }
            }
        });
        // Handle post deletion
        document.getElementById('post-details').addEventListener('click', function(e) {
            if (e.target.id === 'delete-post-button') {
                if (confirm('Are you sure you want to delete this post?')) {
                    axios.delete(`/api/posts/${postId}`)
                        .then(() => {
                            window.location.href = '/';
                        })
                        .catch(error => console.error('Error deleting post:', error));
                }
            }
        });


        // Initial data fetching
        fetchPostDetails();
        fetchComments();
    });
</script>
@endsection