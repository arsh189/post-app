@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<style>
    .shadow-sm {
        display: none;
    }
</style>
<h1>Posts</h1>

<!-- Create Post Button -->
@if(Auth::user()->can('create posts'))
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPostModal">Create Post</button>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#importPostModal">Import</button>
@endif

<div class="row">
    <!-- DataTable -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Content</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($posts as $postIndex => $post)
                <tr>
                <td>{{ ($posts->currentPage() - 1) * $posts->perPage() + $postIndex + 1 }}</td>
                <td>{{ $post->title }}</td>
                    <td>{{ Str::limit($post->content, 50) }}</td>
                    <td>
                        <a href="{{ route('posts.show', $post->id) }}" class="btn btn-primary btn-sm">View</a>
                        @if(Auth::user()->can('edit posts'))
                        <a href="#" class="btn btn-warning btn-sm editPostBtn" data-id="{{$post->id}}" data-title="{{$post->title}}" data-content="{{$post->content}}" data-bs-toggle="modal" data-bs-target="#editPostModal">Edit</a>
                        @endif
                        <button id="deletePost" data-id="{{ $post->id }}" class="btn btn-danger btn-sm delete-button">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    {{ $posts->links() }}

</div>

<!-- Create Post Modal -->
<div class="modal fade" id="createPostModal" tabindex="-1" aria-labelledby="createPostModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createPostModalLabel">Create Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createPostForm">
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea class="form-control" id="content" name="content" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Create</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Post Modal -->
<div class="modal fade" id="editPostModal" tabindex="-1" aria-labelledby="editPostModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPostModalLabel">Edit Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editPostForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editPostId" name="id">
                    <div class="mb-3">
                        <label for="editTitle" class="form-label">Title</label>
                        <input type="text" class="form-control" id="editTitle" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="editContent" class="form-label">Content</label>
                        <textarea class="form-control" id="editContent" name="content" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-warning">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for CSV Upload -->
<div class="modal fade" id="importPostModal" tabindex="-1" aria-labelledby="importPostModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importPostModalLabel">Import Posts (CSV)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <button id="downloadTemplate" class="btn btn-primary">Download CSV Template</button>
                <hr>
                <input type="file" id="csvFile" class="form-control">
                <button class="btn btn-primary mt-3" id="uploadCsv">📤 Upload CSV</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<!-- Include Bootstrap & jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>


<script>
    $(document).ready(function () {

        // Handle Create Post Form
        $("#createPostForm").submit(function (e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('posts.store') }}",
                method: "POST",
                data: $(this).serialize(),
                success: function (response) {
                    Swal.fire("Success", "Post created successfully!", "success");
                    $("#createPostModal").modal("hide");
                    location.reload();
                },
                error: function () {
                    Swal.fire("Error", "Something went wrong!", "error");
                }
            });
        });

        // Open Edit Post Modal & Populate Data
        $(".editPostBtn").click(function () {
            let id = $(this).data("id");
            let title = $(this).data("title");
            let content = $(this).data("content");

            $("#editPostId").val(id);
            $("#editTitle").val(title);
            $("#editContent").val(content);
        });

        // Handle Edit Post Form
        $("#editPostForm").submit(function (e) {
            e.preventDefault();
            let id = $("#editPostId").val();

            $.ajax({
                url: "/posts/" + id, 
                method: "POST",
                data: $(this).serialize(),
                success: function (response) {
                    Swal.fire("Updated", "Post updated successfully!", "success");
                    $("#editPostModal").modal("hide");
                    location.reload();
                },
                error: function () {
                    Swal.fire("Error", "Something went wrong!", "error");
                }
            });
        });
    });

    document.querySelectorAll(".delete-button").forEach(button => {
        button.addEventListener("click", function () {
            let postId = this.getAttribute("data-id"); // Get post ID from data-id attribute

            Swal.fire({
                title: "Are you sure?",
                text: "This action cannot be undone!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/posts/${postId}`, {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            "X-Requested-With": "XMLHttpRequest",
                            "Content-Type": "application/json"
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire("Deleted!", "The post has been deleted.", "success")
                            .then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire("Error!", data.error || "Something went wrong.", "error");
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        Swal.fire("Error!", "Could not delete the post.", "error");
                    });
                }
            });
        });
    });
</script>
<script>
    document.getElementById("uploadCsv").addEventListener("click", function () {
        let fileInput = document.getElementById("csvFile");
        let file = fileInput.files[0];

        if (!file) {
            Swal.fire("Error!", "Please select a CSV file to upload.", "error");
            return;
        }

        if (!file.name.endsWith(".csv")) {
            Swal.fire("Error!", "Only CSV files are allowed.", "error");
            return;
        }

        let formData = new FormData();
        formData.append("csv_file", file);
        formData.append("_token", document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        Swal.fire({
            title: "Uploading...",
            text: "Please wait while we process the file.",
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch("{{ route('posts.import') }}", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && !data.duplicates) {
                Swal.fire({
                    title: "Success!",
                    text: data.uploadCount + " Posts imported successfully! and 0 failed.",
                    icon: "success",
                    confirmButtonText: "OK"
                }).then(() => {
                    location.reload(); // Refresh page
                });
            }

            if (data.duplicates) {
                Swal.fire({
                    title: "Duplicates Found!",
                    text: data.uploadCount + " Posts imported successfully! and " + data.failedCount + " records were already in the database.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Download Duplicates"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = data.duplicate_file; // Download duplicate file
                    }
                });
            } else if (!data.success) {
                Swal.fire("Error!", "Something went wrong.", "error");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            Swal.fire("Error!", "Could not process the file.", "error");
        });
    });
</script>

<script>
    document.getElementById("downloadTemplate").addEventListener("click", function () {
        // Define CSV content (headers only)
        let csvContent = "data:text/csv;charset=utf-8," 
                        + "title,content\n";  // Example headers

        // Create a downloadable link
        let encodedUri = encodeURI(csvContent);
        let link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "csv_template.csv");
        document.body.appendChild(link); // Required for Firefox

        link.click(); // Trigger download
        document.body.removeChild(link); // Cleanup
    });
</script>
@endsection
