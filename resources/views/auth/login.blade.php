<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
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
        input {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
            font-size: 14px;
            text-align: left;
            margin-top: -8px;
            margin-bottom: 10px;
            display: none; /* Initially hidden */
        }
        .errors {
            color: red;
            font-size: 18px;
            text-align: left;
            margin-bottom: 10px;
        }
        .form-group {
            text-align: left;
            margin-bottom: 10px;
        }
        .check-box {
            width: auto;
        }
        .remember-me {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .forgot-password {
            text-align: right;
            display: block;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form id="loginForm" method="POST" action="{{ route('login') }}" onsubmit="return validateLoginForm()">
            @csrf
            <input type="email" id="loginEmail" name="email" placeholder="Email" value="{{ old('email') }}">
            <div class="error" id="loginEmailError"></div>

            <input type="password" id="loginPassword" name="password" placeholder="Password">
            <div class="error" id="loginPasswordError"></div>

            <!-- Remember Me Checkbox -->
            <div class="form-group">
                <label class="remember-me">
                    <input type="checkbox" class="check-box" name="remember"> Remember Me
                </label>
            </div>

            <div class="errors">@error('email') <span>{{ $message }}</span> @enderror</div>
            <button type="submit">Login</button>

            <!-- Forgot Password Link -->
            <!-- <a class="forgot-password" href="{{ route('password.request') }}">Forgot Password?</a> -->
        </form>
        
        <p>Don't have an account? <a href="{{ route('register') }}">Register</a></p>
    </div>

    <script>
        function validateLoginForm() {
            let valid = true;
            let email = document.getElementById("loginEmail").value.trim();
            let password = document.getElementById("loginPassword").value.trim();

            let emailError = document.getElementById("loginEmailError");
            let passwordError = document.getElementById("loginPasswordError");

            // Clear previous errors
            emailError.style.display = "none";
            passwordError.style.display = "none";

            // Email validation
            if (email === "") {
                emailError.innerText = "Email is required.";
                emailError.style.display = "block";
                valid = false;
            } else if (!email.match(/^\S+@\S+\.\S+$/)) {
                emailError.innerText = "Enter a valid email address.";
                emailError.style.display = "block";
                valid = false;
            }

            // Password validation
            if (password === "") {
                passwordError.innerText = "Password is required.";
                passwordError.style.display = "block";
                valid = false;
            } else if (password.length < 6) {
                passwordError.innerText = "Password must be at least 6 characters.";
                passwordError.style.display = "block";
                valid = false;
            }

            return valid;
        }

        // Remove error messages when user starts typing
        document.getElementById("loginEmail").addEventListener("input", function() {
            document.getElementById("loginEmailError").style.display = "none";
        });

        document.getElementById("loginPassword").addEventListener("input", function() {
            document.getElementById("loginPasswordError").style.display = "none";
        });
    </script>
</body>
</html>
