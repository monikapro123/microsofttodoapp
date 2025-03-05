<?php
include 'config/database.php';
if (isset($_SESSION['loginid'])) {  
    header("Location: index.php");
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Form</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <style>
        body {
            background-color: #1E3E62;
            color: #333;
        }
    </style>
</head>

<body class="h-screen flex items-center justify-center">

    <!-- Signup Form Container -->
    <div class="bg-white p-6 rounded-lg max-w-md w-full shadow-lg">
        <h2 class="text-2xl font-semibold mb-6 text-gray-800 text-center">Sign Up</h2>
        <form action="./config/server.php" method="POST" id="signup_Form">
            <input type="hidden" name="signup" value="1">

            <!-- First Name -->
            <input type="text" name="firstname" placeholder="First Name" id="firstname"
                class="w-full mb-4 px-4 py-2 rounded-md bg-gray-100 text-gray-700 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <span id="firstnameerror" class="text-red-500 hidden"></span>

            <!-- Last Name -->
            <input type="text" name="lastname" placeholder="Last Name" id="lastname"
                class="w-full mb-4 px-4 py-2 rounded-md bg-gray-100 text-gray-700 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <span id="lastnameerror" class="text-red-500 hidden"></span>

            <!-- Email -->
            <input type="email" name="email" placeholder="Email" id="email"
                class="w-full mb-4 px-4 py-2 rounded-md bg-gray-100 text-gray-700 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <span id="emailerror" class="text-red-500 hidden"></span>

            <!-- Phone Number -->
            <input type="tel" name="phoneno" placeholder="Phone Number" id="phoneno"
                class="w-full mb-4 px-4 py-2 rounded-md bg-gray-100 text-gray-700 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <span id="phonenoerror" class="text-red-500 hidden"></span>

            <!-- Password -->
            <input type="password" name="password" placeholder="Password" id="password"
                class="w-full mb-4 px-4 py-2 rounded-md bg-gray-100 text-gray-700 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <span id="passworderror" class="text-red-500 hidden"></span>

            <!-- Confirm Password -->
            <input type="password" name="confirmpassword" placeholder="Confirm Password" id="confirmpassword"
                class="w-full mb-6 px-4 py-2 rounded-md bg-gray-100 text-gray-700 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <span id="confirmpassworderror" class="text-red-500 hidden"></span>

            <!-- Submit Button -->
            <button type="submit" id="signup"
                class="w-full py-2 bg-[#1E3E62] text-white rounded-md font-semibold hover:bg-[#0B192C] focus:outline-none focus:ring-2 focus:ring-blue-500">
                Sign Up
            </button>
        </form>
        <div class="text-center mt-2">
            <p class="text-gray-600 text-sm">
                Already have an account?
                <a href="signin.php" class="text-yellow-800 hover:underline"> signin</a>
            </p>
        </div>
        <!-- Form End -->
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

    <script>

        // toastr start 
        // Ensure Toastr is working
toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};

// Test Toastr
// toastr.success('Toastr is working!');


        $(document).on("click", "#signup", function (e) {

            e.preventDefault();

            let firstname = $("#firstname").val().trim();
            let lastname = $("#lastname").val().trim();
            let email = $("#email").val().trim();
            let phoneno = $("#phoneno").val().trim();
            let password = $("#password").val();
            let confirmpassword = $("#confirmpassword").val();

            let errorCount = 0;

            // First Name Validation
            if (firstname === "") {
                $("#firstnameerror").text("Required field").removeClass("hidden");
                $("#firstname").addClass("border border-red-500");
                errorCount++;
            } else {
                $("#firstnameerror").text("").addClass("hidden");
                $("#firstname").removeClass("border border-red-500");
            }

            // Last Name Validation
            if (lastname === "") {
                $("#lastnameerror").text("Required field").removeClass("hidden");
                $("#lastname").addClass("border border-red-500");
                errorCount++;
            } else {
                $("#lastnameerror").text("").addClass("hidden");
                $("#lastname").removeClass("border border-red-500");
            }

            // Email Validation
            let emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (email === "") {
                $("#emailerror").text("Required field").removeClass("hidden");
                $("#email").addClass("border border-red-500");
                errorCount++;
            } else if (!emailPattern.test(email)) {
                $("#emailerror").text("Invalid email format").removeClass("hidden");
                $("#email").addClass("border border-red-500");
                errorCount++;
            } else {
                $("#emailerror").text("").addClass("hidden");
                $("#email").removeClass("border border-red-500");
            }

            // Phone Number Validation
            if (phoneno === "") {
                $("#phonenoerror").text("Required field").removeClass("hidden");
                $("#phoneno").addClass("border border-red-500");
                errorCount++;
            } else {
                $("#phonenoerror").text("").addClass("hidden");
                $("#phoneno").removeClass("border border-red-500");
            }

            // Password Validation
            let validated = true;
            let passwordMessage = "";

            if (password.length < 8) {
                validated = false;
                passwordMessage = "Password must be at least 8 characters.";
            } else if (!/\d/.test(password)) {
                validated = false;
                passwordMessage = "Password must contain at least one number.";
            } else if (!/[a-z]/.test(password)) {
                validated = false;
                passwordMessage = "Password must contain at least one lowercase letter.";
            } else if (!/[A-Z]/.test(password)) {
                validated = false;
                passwordMessage = "Password must contain at least one uppercase letter.";
            } else if (!/[^0-9a-zA-Z]/.test(password)) {
                validated = false;
                passwordMessage = "Password must contain at least one special character.";
            }

            if (!validated) {
                $("#passworderror").text(passwordMessage).removeClass("hidden");
                $("#password").addClass("border border-red-500");
                errorCount++;
            } else {
                $("#passworderror").text("").addClass("hidden");
                $("#password").removeClass("border border-red-500");
            }

            // Confirm Password Validation
            if (confirmpassword === "") {
                $("#confirmpassworderror").text("Required field").removeClass("hidden");
                $("#confirmpassword").addClass("border border-red-500");
                errorCount++;
            } else if (password !== confirmpassword) {
                $("#confirmpassworderror").text("Passwords do not match").removeClass("hidden");
                $("#confirmpassword").addClass("border border-red-500");
                errorCount++;
            } else {
                $("#confirmpassworderror").text("").addClass("hidden");
                $("#confirmpassword").removeClass("border border-red-500");
            }

            if (errorCount == 0) {
                let form = $("#signup_Form");
                let url = form.attr("action");
                $.ajax({
                    type: "POST",
                    url: url,
                    data: form.serialize(),
                    success: function (response) {
                        let arr = JSON.parse(response);
                        if (arr.success == true) {
                            toastr.success('Sign up successful!', 'Success');
                            setTimeout(() => {
                                window.location.href = "signin.php";
                            }, 500);
                        } else {
                            if (arr.email_error) {
                                $("#emailerror").text(arr.email_error).removeClass("hidden");
                                $("#email").addClass("border border-red-500");
                            } else {
                                $("#emailerror").text("").addClass("hidden");
                                $("#email").removeClass("border border-red-500");
                            }

                            if (arr.phoneno_error) {
                                $("#phonenoerror").text(arr.phoneno_error).removeClass("hidden");
                                $("#phoneno").addClass("border border-red-500");
                            } else {
                                $("#phonenoerror").text("").addClass("hidden");
                                $("#phoneno").removeClass("border border-red-500");
                            }
                        }
                    }
                })
            }
        });

        $(document).on("keyup", "#firstname", function () {
            $firstname = $(this).val();
            if (firstname = "") {
                $("#firstnameerror").text("field is required").removeClass("hidden");
                $("#firstname").addClass("border border-red-500");
            } else {
                $("#firstnameerror").text("field is required").addClass("hidden");
                $("#firstname").removeClass("border border-red-500");
            }
        })
        $(document).on("keyup", "#lastname", function () {
            $firstname = $(this).val();
            if (firstname = "") {
                $("#lastnameerror").text("field is required").removeClass("hidden");
                $("#lastname").addClass("border border-red-500");
            } else {
                $("#lastnameerror").text("field is required").addClass("hidden");
                $("#lastname").removeClass("border border-red-500");
            }
        })
        $(document).on("keyup", "#email", function () {
            $firstname = $(this).val();
            if (firstname = "") {
                $("#emailerror").text("field is required").removeClass("hidden");
                $("#email").addClass("border border-red-500");
            } else {
                $("#emailerror").text("field is required").addClass("hidden");
                $("#email").removeClass("border border-red-500");
            }
        })
        $(document).on("keyup", "#phoneno", function () {
            $firstname = $(this).val();
            if (firstname = "") {
                $("#phonenoerror").text("field is required").removeClass("hidden");
                $("#phoneno").addClass("border border-red-500");
            } else {
                $("#phonenoerror").text("field is required").addClass("hidden");
                $("#phoneno").removeClass("border border-red-500");
            }
        })
        $(document).on("keyup", "#password", function () {
            $firstname = $(this).val();
            if (firstname = "") {
                $("#passworderror").text("field is required").removeClass("hidden");
                $("#password").addClass("border border-red-500");
            } else {
                $("#passworderror").text("field is required").addClass("hidden");
                $("#password").removeClass("border border-red-500");
            }
        })
        $(document).on("keyup", "#confirmpassword", function () {
            $firstname = $(this).val();
            if (firstname = "") {
                $("#confirmpassworderror").text("field is required").removeClass("hidden");
                $("#confirmpassword").addClass("border border-red-500");
            } else {
                $("#confirmpassworderror").text("field is required").addClass("hidden");
                $("#confirmpassword").removeClass("border border-red-500");
            }
        })

    </script>

</body>

</html>