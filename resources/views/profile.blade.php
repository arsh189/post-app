@extends('layouts.app')

@section('title', 'Profile Picture')

@section('content')
<div class="container text-center">
    <h2>Update Profile Picture</h2>

    <!-- Profile Picture Preview -->
    <div class="profile-picture-container">
        <img id="currentProfilePic" 
             src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('images/dummy.jpg') }}" 
             class="rounded-circle profile-img" 
             alt="Profile Picture">
    </div>

    <!-- Image Upload Input -->
    <input type="file" id="imageInput" accept="image/*" class="mt-3" style="cursor:pointer;">

    <!-- Image Preview for Cropping -->
    <div class="mt-3">
        <img id="imagePreview" style="max-width: 100%; display: none;">
    </div>

    <!-- Crop & Upload Buttons -->
    <button id="cropButton" class="btn btn-primary mt-3" style="display: none;">Crop & Upload</button>
</div>
@endsection

@section('scripts')
<!-- Include Cropper.js -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    
    let cropper;

    document.getElementById("imageInput").addEventListener("change", function (event) {
        let file = event.target.files[0];

        if (!file) return;

        // Allowed image MIME types
        const allowedTypes = ["image/jpeg", "image/png", "image/gif", "image/webp"];

        // Validate file type (only images)
        if (!allowedTypes.includes(file.type)) {
            Swal.fire("Invalid File", "Please select a valid image (JPG, PNG, GIF, WebP).", "error");
            return;
        }

        // Validate file size (between 500KB and 1MB)
        let fileSizeKB = file.size / 1024; // Convert bytes to KB
        if (fileSizeKB < 500 || fileSizeKB > 1024) {
            Swal.fire("Invalid Size", "Image size must be between 500KB and 1MB.", "warning");
            return;
        }

        let reader = new FileReader();
        reader.onload = function (e) {
            let image = document.getElementById("imagePreview");
            image.src = e.target.result;
            image.style.display = "block";

            if (cropper) {
                cropper.destroy(); // Destroy existing cropper instance
            }

            cropper = new Cropper(image, {
                aspectRatio: 1, // Square crop
                viewMode: 2,
                autoCropArea: 1,
                responsive: true
            });

            document.getElementById("cropButton").style.display = "block";
        };
        reader.readAsDataURL(file);
    });

    document.getElementById("cropButton").addEventListener("click", function () {
        if (cropper) {
            cropper.getCroppedCanvas().toBlob((blob) => {
                let formData = new FormData();
                formData.append("cropped_image", blob);
                formData.append("_token", "{{ csrf_token() }}"); // Laravel CSRF Token

                fetch("{{ route('profile.update') }}", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById("currentProfilePic").src = data.image_url;
                        
                        Swal.fire({
                            title: "Success!",
                            text: "Profile picture updated successfully!",
                            icon: "success",
                            confirmButtonColor: "#3085d6",
                            confirmButtonText: "OK"
                        });

                        // Hide and reset cropper after success
                        cropper.destroy();
                        cropper = null;
                        document.getElementById("imagePreview").style.display = "none"; // Hide preview
                        document.getElementById("cropButton").style.display = "none"; // Hide crop button
                        document.getElementById("imageInput").value = ""; // Reset input field

                    } else {
                        Swal.fire("Upload Failed", "Error uploading image.", "error");
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    Swal.fire("Error", "Something went wrong. Try again.", "error");
                });
            });
        }
    });


</script>

<style>
    .profile-picture-container {
        width: 150px;
        height: 150px;
        overflow: hidden;
        margin: auto;
    }
    .profile-img {
        width: 100%;
        height: auto;
        border-radius: 50%;
    }
</style>
@endsection
