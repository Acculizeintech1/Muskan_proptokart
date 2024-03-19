<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multi-section Form</title>
    <style>
        .form-section {
            display: none;
        }

        .active {
            display: block;
        }

        body {
            background-color: #264553;
        }

        main {
            font-family: cursive;
            /* display: flex; */
            align-items: center;
            text-align: center;
            justify-content: center;
            /* margin: 0 auto; */
        }

        .selection-buttons {
            display: flex;
            flex-direction: row;
            justify-content: center;
        }

        .selection-buttons button {
            padding: 1% 7%;
            /* margin: 1%; */
            /* border: none; */
            background-color: rgb(255 255 255 / 10%);
            border-radius: 9px;
            border: 1px solid black;
            font-family: monospace;
            font-size: 144%;
        }

        .form-container {
            margin: 4% auto;
            font-family: cursive;
            color: black;
            background-color: white;
            border-radius: 45px;
            padding: 2% 8%;
            width: 30%;
        }

        h2 {
            font-size: 310%;
            font-weight: bold;
            margin: 0 0 10% 0;
        }

        input {
            padding: 2% 1% 2% 3%;
            background-color: #f0e7e7;
            border: none;
            font-size: 94%;
            border-bottom: 2px solid black;
            border-radius: 10px;
        }

        main button {
            color: white;
            background-color: #14b2a2;
            border: 3px double #2de095;
            border-radius: 10px;
            padding: 2% 5%;
            font-weight: bold;
            font-size: 115%;
        }

        main button {
            background-color: #1c8c81;
            color: #ffffffd9;
        }

        @media only screen and (max-width: 600px) {
            .form-container {
                width: 75%;
            }

            .form-container input {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <header>
        <?php include "header.html" ?>
    </header>
    <main>
        <div class="selection-buttons">
            <button class="section-btn" data-section="user">User</button>
            <button class="section-btn" data-section="employee">Employee</button>
            <button class="section-btn" data-section="admin">Admin</button>
        </div>

        <div class="form-section user active">
            <div class="form-container">
                <h2>User Section</h2>
                <!-- User form fields here -->
                <label for="user-name">Name:</label>
                <input type="text" id="user-name">
                <!-- Add more user-specific fields as needed -->
            </div>
        </div>

        <div class="form-section employee">
            <div class="form-container">
                <h2>Employee Section</h2>
                <form action="emp_fetch.php" method="post" enctype="multipart/form-data">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" placeholder="UserName"><br><br>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Password"><br><br>
                    <input type="checkbox" id="showPassword">
                    <label for="showPassword">Show Password</label><br><br>
                    <button type="submit">Login</button>
                </form>
                <h6>Create New Account <a href="employee.php">SignUp</a></h6>
            </div>
        </div>

        <div class="form-section admin">
            <div class="form-container">
                <h2>Admin Section</h2>
                <form action="authentication.php" method="post" enctype="multipart/form-data">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" placeholder="UserName"><br><br>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Password"><br><br>
                    <input type="checkbox" id="showPassword">
                    <label for="showPassword">Show Password</label><br><br>
                    <button type="submit">Login</button>
                </form>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const formSections = document.querySelectorAll('.form-section');
            const sectionButtons = document.querySelectorAll('.section-btn');

            sectionButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const section = button.getAttribute('data-section');

                    formSections.forEach(sectionElement => {
                        if (sectionElement.classList.contains(section)) {
                            sectionElement.classList.add('active');
                        } else {
                            sectionElement.classList.remove('active');
                        }
                    });
                });
            });
        });

        // Function to toggle password visibility
        function togglePasswordVisibility() {
            var passwordInput = document.getElementById("password");
            var showPasswordCheckbox = document.getElementById("showPassword");

            // If checkbox is checked, show password, otherwise hide it
            if (showPasswordCheckbox.checked) {
                passwordInput.type = "text";
            } else {
                passwordInput.type = "password";
            }
        }

        // Attach event listener to the checkbox
        document.getElementById("showPassword").addEventListener("change", togglePasswordVisibility);
    </script>
</body>

</html>