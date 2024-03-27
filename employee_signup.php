<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    include "connection.php";

    $sql_create_employee_table = "CREATE TABLE IF NOT EXISTS Employee (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        Username VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        phone VARCHAR(20) NOT NULL,
        password VARCHAR(255) NOT NULL
    )";

    // Execute the query to create the add_property table
    if ($conn->query($sql_create_employee_table) === TRUE) {
        echo "Table Employee created successfully.<br>";
    } else {
        echo "Error creating table Employee: " . $conn->error . "<br>";
    }

    // Retrieve user input and sanitize
    $name = $conn->real_escape_string($_POST['name']);
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $password = $conn->real_escape_string($_POST['password']);

    // Create a new table for the employee
    // $employee_table_name = $username; // Use employee username as table name
    $employee_table_name = $username; // Use employee username as table name
    $backup_identifier = uniqid(); // Generates a unique identifier

    $sql_check_employee_entry = "SELECT COUNT(*) as count FROM employee WHERE username = ?";
    $stmt_check_employee = $conn->prepare($sql_check_employee_entry);
    $stmt_check_employee->bind_param("s", $username);
    $stmt_check_employee->execute();
    $result = $stmt_check_employee->get_result();
    $row = $result->fetch_assoc();
    $stmt_check_employee->close();

    if ($row['count'] != 0) {
        echo "<script>alert('$username Already exist. Try another'); window.location.href = 'employee_signup.php';</script>";
    } else {

        // Create the main table
        $sql_create_main_table = "CREATE TABLE IF NOT EXISTS $employee_table_name (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            address VARCHAR(100) NOT NULL,
            owner_name VARCHAR(50),
            owner_email VARCHAR(50),
            owner_phone VARCHAR(15),
            description TEXT,
            price DECIMAL(10,2)
        )";

        if ($conn->query($sql_create_main_table) === TRUE) {
            // Create the image table with foreign key constraint
            $sql_create_image_table = "CREATE TABLE IF NOT EXISTS {$employee_table_name}_image (
                image_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                id INT(6) UNSIGNED,
                image_name VARCHAR(255),
                image_data LONGBLOB,
                image_type VARCHAR(100),
                FOREIGN KEY (id) REFERENCES $employee_table_name(id) ON DELETE CASCADE
            )";

            if ($conn->query($sql_create_image_table) === TRUE) {
                // Create the video table with foreign key constraint
                $sql_create_video_table = "CREATE TABLE IF NOT EXISTS {$employee_table_name}_video (
                    video_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    id INT(6) UNSIGNED,
                    video_name VARCHAR(255),
                    video_data LONGBLOB,
                    video_type VARCHAR(100),
                    FOREIGN KEY (id) REFERENCES $employee_table_name(id) ON DELETE CASCADE
                )";

                if ($conn->query($sql_create_video_table) === TRUE) {
                    // Insert data into the employee table

                    $sql_employee = "INSERT INTO employee (name, Username, email, phone, password) VALUES (?,?, ?, ?, ?)";
                    $stmt_employee = $conn->prepare($sql_employee);
                    $stmt_employee->bind_param("sssss", $name, $username, $email, $phone, $password);
                    if ($stmt_employee->execute()) {
                        $employee_id = $stmt_employee->insert_id;
                        $stmt_employee->close();
                        // Redirect to login form after successful account creation
                        echo "<script>alert('Account Created Successfully with UserName $username'); window.location.href = 'employee_signup.php';</script>";
                    }
                } else {
                    // Failure alert for video table creation
                    echo "<script>alert('Error creating video table. Please try again later.');</script>";
                }
            } else {
                // Failure alert for image table creation
                echo "<script>alert('Error creating image table. Please try again later.');</script>";
            }
        } else {
            // Failure alert for main table creation
            echo "<script>alert('Error creating main table. Please try again later. <br> Make sure your UserName does not contain any Spaces');</script>";
        }
    }

    $conn->close();
}
?>


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
        <!--  -->
        <div class="form-container">
            <h2>Create Account</h2>
            <form method="post" enctype="multipart/form-data">
                <label for="name">Emp Name:</label>
                <input type="text" id="name" name="name" placeholder="Name" required><br><br>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" placeholder="UserName" required><br><br>
                <label for="email">Email Id : </label>
                <input type="email" id="email" name="email" placeholder="UserName@gmail.com" required><br><br>
                <label for="phone">Phone No.:</label>
                <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required><br><br>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Password" required><br><br>
                <input type="checkbox" id="showPassword">
                <label for="showPassword">Show Password</label><br><br>
                <button type="submit">Submit</button>
            </form>
        </div>

    </main>

    <script src="Script.js"></script>
</body>

</html>