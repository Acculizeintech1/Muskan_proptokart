<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "office";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die ("Connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        #loading {
            display: none;
            text-align: center;
            margin-top: 20px;
            /* Adjust as needed */
        }

        #mediaContainer h2 {
            font-size: 220%;
            /* font-weight: bold; */
            margin: 2%;
            font-family: emoji;
        }

        a {
            text-decoration: none;
            color: white;
        }

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
            margin: 0 auto;
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

        .selection-buttons button:hover {
            background-color: rgb(255 255 255 / 20%);
        }

        .form-container {
            margin: 4% auto;
            font-family: cursive;
            color: black;
            background-color: white;
            border-radius: 45px;
            padding: 2% 2%;
            width: 90%;
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
                <h1>Employee Lists</h1>
                <table border="5" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact Number</th>
                            <th>Password</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT id,username,email,phone,password FROM employee";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["id"] . "</td>";
                                echo "<td>" . $row["username"] . "</td>";
                                echo "<td>" . $row["email"] . "</td>";
                                echo "<td>" . $row["phone"] . "</td>";
                                echo "<td>" . $row["password"] . "</td>";
                                echo "<td>
                                <form action='emp_fetch.php' method='post' enctype='multipart/form-data'>
                                    <input type='hidden' name='username' value='" . $row["username"] . "'>
                                    <input type='hidden' name='password' value='" . $row["password"] . "'>
                                    <button type='submit'>Show Data</button>
                                </form>
                              </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No users found</td></tr>";
                        }
                        $conn->close();
                        ?>
                    </tbody>
                </table>
                <button onclick="window.location.href='employee_remove.php'"
            style="position: fixed; bottom: 50px; right: 20px; background-color: #2fc595; color: white; padding: .5% 1%; border: 4px double black; font-weight: bold; font-family: monospace; font-size: 100%;">Remove
            Emp </button>
                <button onclick="window.location.href='employee_signup.php'"
            style="position: fixed; bottom: 8px; right: 20px; background-color: #2fc595; color: white; padding: .5% 1%; border: 4px double black; font-weight: bold; font-family: monospace; font-size: 100%;">Add
            Employee </button>
            </div>

        </div>

        <div class="form-section admin">
            <div class="form-container">
                <h1>Property Listings</h1>
                <table border="5" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Address of the Property</th>
                            <th>Contact Number</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $conn = new mysqli($servername, $username, $password, $dbname);

                        if ($conn->connect_error) {
                            die ("Connection failed: " . $conn->connect_error);
                        }

                        $sql = "SELECT id, name, place, price FROM add_property";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["id"] . "</td>";
                                echo "<td>" . $row["name"] . "</td>";
                                echo "<td>" . $row["place"] . "</td>";
                                echo "<td>" . $row["price"] . "</td>";
                                echo "<td><button onclick='showMedia(" . $row["id"] . ")'>Show Media</button></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No users found</td></tr>";
                        }
                        $conn->close();
                        ?>
                    </tbody>
                </table>
                <div id="loading">Loading...</div>
                <div id="mediaContainer"></div>
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

        function showMedia(userId) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        document.getElementById("mediaContainer").innerHTML = xhr.responseText;
                        document.getElementById("loading").style.display = "none";
                    } else {
                        document.getElementById("mediaContainer").innerHTML = "Error loading media.";
                        document.getElementById("loading").style.display = "none";
                    }
                }
            };
            xhr.open("GET", "fetch_media.php?userId=" + userId, true);
            xhr.send();
            document.getElementById("loading").style.display = "block";
        }

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