<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    include "connection.php";

    // Retrieve user input and sanitize
    $username = $conn->real_escape_string($_POST['username']);

    // Create a new table for the employee
    $employee_table_name = $username; // Use employee username as table name

    // Create a temporary staging table for the employee data
    $staging_table_name = "temp_staging_" . $username;
    $sql_create_staging_table = "CREATE TABLE IF NOT EXISTS $staging_table_name LIKE $employee_table_name";

    $staging_table_name_image = "temp_staging_image_" . $username; // Corrected syntax
    $sql_create_staging_table_image = "CREATE TABLE IF NOT EXISTS $staging_table_name_image LIKE {$employee_table_name}_image";

    $staging_table_name_video = "temp_staging_video_" . $username; // Corrected syntax
    $sql_create_staging_table_video = "CREATE TABLE IF NOT EXISTS $staging_table_name_video LIKE {$employee_table_name}_video";

    if ($conn->query($sql_create_staging_table) === TRUE && $conn->query($sql_create_staging_table_image) === TRUE && $conn->query($sql_create_staging_table_video) === TRUE) {

        // Copy data from username, username_image, and username_video to the staging table
        $sql_copy_username_data = "INSERT INTO $staging_table_name SELECT * FROM $employee_table_name";
        $sql_copy_image_data = "INSERT INTO $staging_table_name_image SELECT * FROM {$employee_table_name}_image";
        $sql_copy_video_data = "INSERT INTO $staging_table_name_video SELECT * FROM {$employee_table_name}_video";

        if ($conn->query($sql_copy_username_data) === TRUE && $conn->query($sql_copy_image_data) === TRUE && $conn->query($sql_copy_video_data) === TRUE) {
            // Ensure the existence of backup tables and add the extra username column
            $sql_create_backup_main = "CREATE TABLE IF NOT EXISTS proptokart (
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                address VARCHAR(100) NOT NULL,
                owner_name VARCHAR(50),
                owner_email VARCHAR(50),
                owner_phone VARCHAR(15),
                description TEXT,
                price DECIMAL(10,2),
                username VARCHAR(30)
            )";
            $conn->query($sql_create_backup_main);

            $sql_create_backup_image = "CREATE TABLE IF NOT EXISTS proptokart_image (
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                proptokart_id INT(6) UNSIGNED,
                image_name VARCHAR(255),
                image_data LONGBLOB,
                image_type VARCHAR(100),
                FOREIGN KEY (proptokart_id) REFERENCES proptokart(id) ON DELETE CASCADE
            )";
            $conn->query($sql_create_backup_image);

            $sql_create_backup_video = "CREATE TABLE IF NOT EXISTS proptokart_video (
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                proptokart_id INT(6) UNSIGNED,
                video_name VARCHAR(255),
                video_data LONGBLOB,
                video_type VARCHAR(100),
                FOREIGN KEY (proptokart_id) REFERENCES proptokart(id) ON DELETE CASCADE
            )";
            $conn->query($sql_create_backup_video);

            // Copy the staging table data to backup tables with adjusted IDs
            $sql_copy_to_backup_main = "INSERT INTO proptokart (address, owner_name, owner_email, owner_phone, description, price, username) SELECT address, owner_name, owner_email, owner_phone, description, price, '$username' FROM $staging_table_name";
            $conn->query($sql_copy_to_backup_main);

            // Update the staging table image and video data with proptokart ID
            $sql_update_image_proptokart_id = "UPDATE $staging_table_name_image SET proptokart_id = (SELECT id FROM proptokart WHERE username = '$username')";
            $conn->query($sql_update_image_proptokart_id);

            $sql_update_video_proptokart_id = "UPDATE $staging_table_name_video SET proptokart_id = (SELECT id FROM proptokart WHERE username = '$username')";
            $conn->query($sql_update_video_proptokart_id);

            $sql_copy_to_backup_image = "INSERT INTO proptokart_image (proptokart_id, image_name, image_data, image_type) SELECT proptokart_id, image_name, image_data, image_type FROM $staging_table_name_image WHERE image_name IS NOT NULL";
            $conn->query($sql_copy_to_backup_image);

            $sql_copy_to_backup_video = "INSERT INTO proptokart_video (proptokart_id, video_name, video_data, video_type) SELECT proptokart_id, video_name, video_data, video_type FROM $staging_table_name_video WHERE video_name IS NOT NULL";
            $conn->query($sql_copy_to_backup_video);

            // Drop the staging tables
            $sql_drop_staging_table = "DROP TABLE IF EXISTS $staging_table_name";
            $conn->query($sql_drop_staging_table);

            $sql_drop_staging_table_image = "DROP TABLE IF EXISTS $staging_table_name_image";
            $conn->query($sql_drop_staging_table_image);

            $sql_drop_staging_table_video = "DROP TABLE IF EXISTS $staging_table_name_video";
            $conn->query($sql_drop_staging_table_video);

            // Drop the employee table
            $sql_drop_employee_table = "DROP TABLE IF EXISTS $employee_table_name";
            $conn->query($sql_drop_employee_table);

            $sql_check_proptokart_entry = "SELECT COUNT(*) as count FROM employee WHERE Username = 'proptokart'";
            $result = $conn->query($sql_check_proptokart_entry);
            $row = $result->fetch_assoc();
            if ($row['count'] == 0) {
                // Insert an entry with the name "proptokart" into the employee table if it doesn't exist
                $sql_insert_proptokart_entry = "INSERT INTO employee (Username) VALUES ('proptokart')";
                $conn->query($sql_insert_proptokart_entry);
            }

            // Delete user row from employees table
            $sql_delete_user = "DELETE FROM employee WHERE UserName = '$username'";
            if ($conn->query($sql_delete_user) === TRUE) {
                // Redirect to login form after successful account deletion
                echo "<script>alert('Account Deleted Successfully with UserName: $username'); window.location.href = 'employee.php';</script>";
            } else {
                // Failure alert for deleting user row
                echo "<script>alert('Error deleting user row. Please try again later.');</script>";
            }
        } else {
            // Failure alert for copying data to staging table
            echo "<script>alert('Error copying data to staging table. Please try again later.');</script>";
        }
    } else {
        // Failure alert for creating staging table
        echo "<script>alert('Error creating staging table. Please try again later.');</script>";
    }

    $conn->close();
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remove Employee</title>
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