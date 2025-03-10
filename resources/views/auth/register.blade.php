<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            width: 100%;
            max-width: 400px;
            background: #fff;
            padding: 20px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            text-align: center;
            box-sizing: border-box;
        }

        input, select {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        h2 {
            margin-bottom: 15px;
            color: #333;
        }

        .error {
            color: red;
            font-size: 14px;
            text-align: left;
            margin-bottom: 5px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .login-link {
            display: block;
            margin-top: 15px;
            font-size: 14px;
        }

        .login-link a {
            color: #007bff;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Register</h2>
        <form id="registerForm" method="POST" action="{{ route('register') }}" onsubmit="return validateRegisterForm()" novalidate>
            @csrf

            <input type="text" id="name" name="name" placeholder="Full Name" value="{{ old('name') }}">
            <div class="error" id="nameError">@error('name') {{ $message }}  @enderror</div>

            <input type="email" id="email" name="email" placeholder="Email" value="{{ old('email') }}">
            <div class="error" id="emailError">@error('email') {{ $message }}  @enderror</div>

            <input type="password" id="password" name="password" placeholder="Password">
            <div class="error" id="passwordError">@error('password') {{ $message }}  @enderror</div>

            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password">
            <div class="error" id="confirmPasswordError"></div>

            <select id="role" name="role">
                <option value="">Select Role</option>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }} >{{ ucfirst($role->name) }}</option>
                @endforeach
            </select>
            <div class="error" id="roleError">@error('role') {{ $message }}  @enderror</div>

            <button type="submit">Register</button>
        </form>

        <div class="login-link">
            Already have an account? <a href="{{ route('login') }}">Login</a>
        </div>
    </div>

    <script>
        function validateRegisterForm() {
            let valid = true;

            // Get values
            let name = document.getElementById("name").value.trim();
            let email = document.getElementById("email").value.trim();
            let password = document.getElementById("password").value.trim();
            let confirmPassword = document.getElementById("password_confirmation").value.trim();
            let role = document.getElementById("role").value.trim();

            // Reset errors
            document.getElementById("nameError").innerText = "";
            document.getElementById("emailError").innerText = "";
            document.getElementById("passwordError").innerText = "";
            document.getElementById("confirmPasswordError").innerText = "";
            document.getElementById("roleError").innerText = "";

            // Name validation
            if (name === "") {
                document.getElementById("nameError").innerText = "Name is required.";
                valid = false;
            } else if (name.length < 3) {
                document.getElementById("nameError").innerText = "Name must be at least 3 characters long.";
                valid = false;
            }

            // Email validation
            if (email === "") {
                document.getElementById("emailError").innerText = "Email is required.";
                valid = false;
            } else if (!email.match(/^\S+@\S+\.\S+$/)) {
                document.getElementById("emailError").innerText = "Please enter a valid email address.";
                valid = false;
            }

            // Password validation
            if (password === "") {
                document.getElementById("passwordError").innerText = "Password is required.";
                valid = false;
            } else if (password.length < 6) {
                document.getElementById("passwordError").innerText = "Password must be at least 6 characters.";
                valid = false;
            }

            // Confirm Password validation
            if (confirmPassword === "") {
                document.getElementById("confirmPasswordError").innerText = "Confirm password is required.";
                valid = false;
            } else if (password !== confirmPassword) {
                document.getElementById("confirmPasswordError").innerText = "Passwords do not match.";
                valid = false;
            }

            // Role validation
            if (role === "") {
                document.getElementById("roleError").innerText = "Please select a role.";
                valid = false;
            }

            return valid;
        }

        // Remove error when user starts typing
        document.getElementById("name").addEventListener("input", function() {
            document.getElementById("nameError").innerText = "";
        });

        document.getElementById("email").addEventListener("input", function() {
            document.getElementById("emailError").innerText = "";
        });

        document.getElementById("password").addEventListener("input", function() {
            document.getElementById("passwordError").innerText = "";
        });

        document.getElementById("password_confirmation").addEventListener("input", function() {
            document.getElementById("confirmPasswordError").innerText = "";
        });

        document.getElementById("role").addEventListener("change", function() {
            document.getElementById("roleError").innerText = "";
        });
    </script>

</body>
</html>
