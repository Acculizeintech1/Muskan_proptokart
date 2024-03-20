<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "office";

    // Establish database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die ("Connection failed: " . $conn->connect_error);
    }

    // Retrieve user input and sanitize
    $username = $conn->real_escape_string($_POST['username']);

    // Create a new table for the employee
    $employee_table_name = $username; // Use employee username as table name

    // Create the main table
    $sql_create_main_table = "INSERT INTO proptokart SELECT * FROM $username";
    

    if ($conn->query($sql_create_main_table) === TRUE) {
        // Create the image table with foreign key constraint
        $sql_create_image_table = "INSERT INTO proptokart_image SELECT * FROM {$username}_image";

        if ($conn->query($sql_create_image_table) === TRUE) {
            // Create the video table with foreign key constraint
            $sql_create_video_table = "INSERT INTO proptokart_video SELECT * FROM {$username}_video";

            if ($conn->query($sql_create_video_table) === TRUE) {
                // Delete tables
                $sql_delete_tables = "DROP TABLE if exists $username, {$username}_image, {$username}_video";
                if ($conn->query($sql_delete_tables) === TRUE) {
                    // Delete user row from employees table
                    $sql_delete_user = "DELETE FROM employee WHERE username = $username";
                    $stmt_delete_user = $conn->prepare($sql_delete_user);
                    $stmt_delete_user->bind_param("s", $username);
                    if ($stmt_delete_user->execute()) {
                        // Redirect to login form after successful account deletion
                        echo "<script>alert('Account Deleted Successfully with UserName: $username'); window.location.href = 'employee.php';</script>";
                    } else {
                        // Failure alert for deleting user row
                        echo "<script>alert('Error deleting user row. Please try again later.');</script>";
                    }
                } else {
                    // Failure alert for deleting tables
                    echo "<script>alert('Error deleting tables. Please try again later.');</script>";
                }
            } else {
                // Failure alert for video table creation
                echo "<script>alert('Error copying video table. Please try again later.');</script>";
            }
        } else {
            // Failure alert for image table creation
            echo "<script>alert('Error copying image table. Please try again later.');</script>";
        }
    } else {
        // Failure alert for main table creation
        echo "<script>alert('Error creating main table. Please try again later. <br> Make sure your UserName does not contain any Spaces');</script>";
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
        <div class="form-container">
            <h2>Delete Account</h2>
            <form method="post" enctype="multipart/form-data">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" placeholder="UserName" required><br><br>
                <button type="submit">Submit</button>
            </form>
        </div>

    </main>
</body>

</html>