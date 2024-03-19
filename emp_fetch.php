<?php
// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Establish database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "office";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die ("Connection failed: " . $conn->connect_error);
    }

    // Retrieve username and password from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Set a cookie to remember the username for 1 hour
    setcookie("username", $username, time() + 3600, "/");

    
    // SQL query to validate username and password
    $sql = "SELECT * FROM employee WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            // If username and password match, fetch all data from the table named same as username
            $table_name = $username; // Assuming table name is same as username
            $sql_fetch_data = "SELECT * FROM $table_name";
            $data_result = $conn->query($sql_fetch_data);

            if ($data_result) { 
            } else {
                // echo "Error retrieving data: " . $conn->error;
                $_SESSION['error'] = "Error retrieving data:";
                echo "<script>alert('Error retrieving data:'); window.location.href = 'login_form.php';</script>";
                exit;
            }
        } else {
            // echo "Invalid username or password.";
            $_SESSION['error'] = "Incorrect username or password";
            echo "<script>alert('Incorrect username or password'); window.location.href = 'login_form.php';</script>";
            exit;
        }
    } else {
        // echo "Error executing SQL query: " . $conn->error;
        $_SESSION['error'] = "Error executing SQL query:";
        echo "<script>alert('Error executing SQL query:'); window.location.href = 'login_form.php';</script>";
        exit;
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Listings</title>
    <link rel="stylesheet" href="css\property.css" />
</head>

<body>
    <header>
        <?php include "header.html" ?>
    </header>
    <main>
        <h1>Property Listings</h1>
        <table border="5">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Address</th>
                    <th>Owner Name</th>
                    <th>Owner Email</th>
                    <th>Owner Phone</th>
                    <th>Price</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <!-- This part will be filled by PHP code -->
                <?php
                if ($data_result->num_rows > 0) {
                    while ($row = $data_result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . $row["address"] . "</td>";
                        echo "<td>" . $row["owner_name"] . "</td>";
                        echo "<td>" . $row["owner_email"] . "</td>";
                        echo "<td>" . $row["owner_phone"] . "</td>";
                        echo "<td>" . $row["price"] . "</td>";
                        echo "<td>" . $row["description"] . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "No data found in the table.";
                } ?>
            </tbody>
        </table>
        <button onclick="window.location.href='add_data.php'" style="position: fixed; bottom: 20px; right: 20px; background-color: #2fc595; color: white; padding: .5% 1%; border: 4px double black; font-weight: bold; font-family: monospace; font-size: 168%;">Add
            Data</button>
    </main>
</body>

</html>