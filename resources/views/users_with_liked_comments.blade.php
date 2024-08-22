@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Users with Liked Comments on Their Posts</h2>

    <div id="users-list" class="row">
        <!-- Users with liked comments will be dynamically inserted here -->
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        axios.get('/api/users-with-liked-comments')
            .then(response => {
                const users = response.data;
                let usersHtml = '';

                users.forEach(user => {
                    usersHtml += `
                        <div class="col-md-12 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">${user.name}</h4>
                                    <p class="card-text"><strong>Email:</strong> ${user.email}</p>
                                    <h5 class="card-title mt-3">Posts:</h5>
                                    <ul>
                    `;

                    user.posts.forEach(post => {
                        usersHtml += `
                            <li class="mt-2">
                                <h6>${post.title}</h6>
                                <p>${post.body}</p>
                                <h6>Comments:</h6>
                                <ul>
                        `;

                        post.comments.forEach(comment => {
                            usersHtml += `
                                <li>
                                    <p><strong>Comment:</strong> ${comment.comment}</p>
                                    <p><strong>Likes:</strong> ${comment.likes.length}</p>
                                </li>
                            `;
                        });

                        usersHtml += `
                                </ul>
                            </li>
                        `;
                    });

                    usersHtml += `
                                    </ul>
                                </div>
                            </div>
                        </div>
                    `;
                });

                document.getElementById('users-list').innerHTML = usersHtml;
            })
            .catch(error => {
                console.error("There was an error fetching the users:", error);
            });
    });
</script>
@endsection
