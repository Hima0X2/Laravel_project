<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Posts</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery CDN -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between mb-3">
            <div>
                <!-- Add New button on the left -->
                <button id="addNewBtn" class="btn btn-primary">Add New</button>
                <!-- Logout button on the left -->
                <button id="logoutBtn" class="btn btn-danger">Logout</button>
            </div>
            <h1>All Posts</h1>
        </div>

        <!-- Display success message if available -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered" id="postsTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>View</th>
                    <th>Update</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <!-- Posts will be dynamically inserted here -->
            </tbody>
        </table>
    </div>

    <!-- jQuery AJAX Script -->
    <script>
        // Check if user is logged in
        function checkLogin() {
            const token = localStorage.getItem('api_token');
            if (!token) {
                // If no token, redirect to login page
                alert('You are not logged in. Redirecting to login page.');
                window.location.href = '/login'; // Redirect to login page
                return false;
            }
            return true;
        }

        // Logout function with token handling
        document.querySelector('#logoutBtn').addEventListener('click', function() {
            const token = localStorage.getItem('api_token');
            if (!token) {
                alert('No token found, redirecting to login');
                window.location.href = '/login'; // Redirect if no token is found
                return;
            }

            fetch('/api/logout', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Logout failed');
                }
                return response.json();
            })
            .then(data => {
                console.log('Logout success:', data);
                localStorage.removeItem('api_token'); // Clear token from local storage
                window.location.href = '/login'; // Redirect to login page
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Logout failed. Please try again.');
            });
        });

        // Navigate to the add new post page when the "Add New" button is clicked
        document.querySelector('#addNewBtn').addEventListener('click', function() {
            window.location.href = '/post/create'; // Assuming the "create post" route is /post/create
        });

        // Fetch and display posts
        function fetchPosts() {
            const token = localStorage.getItem('api_token');  // Retrieve the token from localStorage
            $.ajax({
                url: '/api/post',
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`,  // Include token in the headers
                },
                success: function(response) {
                    console.log(response);  // Log response to check if posts are returned
                    const posts = response.data;
                    let rows = '';
                    posts.forEach(post => {
                        rows += `
                            <tr>
                                <td>${post.id}</td>
                                <td>${post.title}</td>
                                <td>${post.description}</td>
                                <td>
                                    ${post.image ? '<img src="/uploads/' + post.image + '" alt="Post Image" width="100">' : 'No Image'}
                                </td>
                                <td><a href="/post/${post.id}" class="btn btn-info btn-sm">View</a></td>
                                <td><a href="/post/edit/${post.id}" class="btn btn-warning btn-sm">Update</a></td>
                                <td><button class="btn btn-danger btn-sm" onclick="deletePost(${post.id})">Delete</button></td>
                            </tr>
                        `;
                    });
                    $('#postsTable tbody').html(rows);  // Insert rows into the table body
                },
                error: function(xhr) {
                    console.error(xhr.responseJSON.message || 'An error occurred');
                }
            });
        }

        // Delete post function
        function deletePost(id) {
            if (confirm('Are you sure you want to delete this post?')) {
                const token = localStorage.getItem('api_token');
                $.ajax({
                    url: `/api/post/${id}`,
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json',
                    },
                    success: function(response) {
                        $('#successMessage').text(response.message).show();
                        fetchPosts(); // Refresh the posts list
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON.message || 'An error occurred');
                    }
                });
            }
        }

        // Fetch posts on page load
        $(document).ready(function() {
            if (checkLogin()) {  // Check if user is logged in before fetching posts
                fetchPosts();
            }
        });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
