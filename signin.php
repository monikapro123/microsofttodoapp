<?php
include 'config/database.php';
if(isset($_SESSION['loginid'])){
    header("Location: index.php");
}
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color:#1E3E62;
            color: #333;
        }
    </style>
</head>

<body class="h-screen flex items-center justify-center">

    <!-- Login Form Container -->
    <div class="bg-white p-6 rounded-lg max-w-md w-full shadow-lg">
        <h2 class="text-2xl font-semibold mb-6 text-gray-800 text-center">Login</h2>
        <form action="./config/server.php" method="POST" id="signin_form">
            <input type="hidden" name="signin" value="1">
            <!-- Email -->
            <input type="email" name="email" placeholder="Email" id="email"
                class="w-full mb-4 px-4 py-2 rounded-md bg-gray-100 text-gray-700 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <small class="text-red-500 text-sm hidden" id="emailerror"></small>

            <!-- Password -->
            <input type="password" name="password" placeholder="Password" id="password"
                class="w-full mb-6 px-4 py-2 rounded-md bg-gray-100 text-gray-700 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <small class="text-red-500 text-sm hidden" id="passworderror"></small>

            <!-- Submit Button -->
            <button type="submit" id="signinbtn"
                class="w-full py-2 bg-[#1E3E62] text-white rounded-md font-semibold hover:bg-[#0B192C] focus:outline-none focus:ring-2 focus:ring-blue-500">
                Sign In
            </button>
        </form>
        <div class="text-center mt-2">
            <p class="text-gray-600 text-sm">
                Don't have an account? Create one
                <a href="signup.php" class="text-yellow-800 hover:underline"> Sign Up</a>
            </p>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        $(document).on('click', '#signinbtn', function (e) {
            e.preventDefault();
            let email = $('#email').val().trim();
            let password = $('#password').val().trim();
            let errorcount = 0
            if (email == "") {
                $('#emailerror').removeClass('hidden').text('Email is required');
                $('#email').addClass('border-red-500');
                errorcount++
            } else {
                $('#emailerror').addClass('hidden').text('');
                $('#email').removeClass('border-red-500');
            }
            if (password == "") {
                $('#passworderror').removeClass('hidden').text('Password is required');
                $('#password').addClass('border-red-500');
                errorcount++
            } else {
                $('#passworderror').addClass('hidden').text('');
                $('#password').removeClass('border-red-500');
            }
            if (errorcount == 0) {
                let form = $('#signin_form');
                let url = form.attr('action');

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: form.serialize(),
                    success: function (response) {
                        // alert(response);
                        console.log(response);

                        let arr = JSON.parse(response);
                        if (arr.success) {
                            console.log('Login successfully');
                            setTimeout(() => {
                                window.location.href = 'index.php';
                            }, 500);
                        } else {
                            if (arr.email_error) {
                                $('#emailerror').removeClass('hidden').text(arr.email_error);
                                $('#email').addClass('border-red-500');
                            }
                            else {
                                $('#emailerror').addClass('hidden').text('');
                                $('#email').removeClass('border-red-500');
                            }
                            if (arr.password_error) {
                                $('#passworderror').removeClass('hidden').text(arr.password_error);
                                $('#password').addClass('border-red-500');
                            }
                            else {
                                $('#passworderror').addClass('hidden').text('');
                                $('#password').removeClass('border-red-500');
                            }
                        }
                    },
                    error: function (error) {
                        console.log('not working');
                    },
                });
            }
        });

        $(document).on('keyup', '#email', function (e) {
            $('#emailerror').addClass('hidden').text('');
            $('#email').removeClass('border-red-500');
        });
        $(document).on('keyup', '#password', function (e) {
            $('#passworderror').addClass('hidden').text('');
            $('#password').removeClass('border-red-500');
        });

    </script>

</body>

</html>