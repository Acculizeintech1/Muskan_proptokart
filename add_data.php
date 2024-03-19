<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "office";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die ("Connection failed: " . $conn->connect_error);
    }
    $username = $_COOKIE['username'];
    $sql = "INSERT INTO $username (address, owner_name, owner_email, owner_phone, price, description) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $_POST['Address'], $_POST['owner_name'], $_POST['owner_email'], $_POST['owner_phone'], $_POST['price'], $_POST['description']);
    $stmt->execute();
    $property_id = $stmt->insert_id;
    $stmt->close();

    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
        // Create a new prepared statement object for image insertion
        $sql = "INSERT INTO {$username}_image (image_data) VALUES (?)";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die ("Error in SQL query: " . $conn->error);
        }

        $image_data = file_get_contents($tmp_name);
        $null = NULL;
        $stmt->bind_param("b", $null);
        $stmt->send_long_data(1, $image_data);
        $stmt->execute();
        $stmt->close();
    }

    foreach ($_FILES['videos']['tmp_name'] as $key => $tmp_name) {
        // Create a new prepared statement object for video insertion
        $sql = "INSERT INTO {$username}_video (video_data) VALUES (?)";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die ("Error in SQL query: " . $conn->error);
        }

        $video_data = file_get_contents($tmp_name);
        $null = NULL;
        $stmt->bind_param("b", $null);
        $stmt->send_long_data(1, $video_data);
        $stmt->execute();
        $stmt->close();
    }


    $conn->close();
    echo "<script>alert('Property Added to $username Successfully'); window.location.href = 'login_form.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Data to your Account</title>
    <link rel="stylesheet" href="css\property.css">
</head>

<body>
    <header>
        <?php include "header.html" ?>
    </header>
    <main>
        <div class="upper">
            <h6>
                <?php if (isset ($_COOKIE['username'])) {
                    $username = $_COOKIE['username'];
                    echo "HELLO, $username!";
                } else {
                    echo "Welcome!";
                } ?>
            </h6>
            <h3>Add New Data to your Account</h3>
        </div>
        <div class="lower">
            <div class="right col-l-12 col-m-12 col-s-12">
                <form method="post" enctype="multipart/form-data">
                    <label for="Address">Address:</label><br>
                    <input type="text" id="Address" name="Address" autocomplete="Address" required><br><br>

                    <div class="media-container">
                        <div class="size col-l-6 col-m-6 col-s-12">
                            <label for="owner_name">Owner Name:</label><br>
                            <input type="text" id="owner_name" name="owner_name" autocomplete="owner_name"
                                required><br><br>
                        </div>

                        <div class="size col-l-6 col-m-6 col-s-12">
                            <label for="owner_email">Owner Email:</label><br>
                            <input type="email" id="owner_email" name="owner_email" autocomplete="owner_email"
                                required><br><br>
                        </div>
                    </div>

                    <div class="media-container">
                        <div class="size col-l-6 col-m-6 col-s-12">
                            <label for="owner_phone">Owner phone:</label><br>
                            <input type="tel" id="owner_phone" name="owner_phone" autocomplete="owner_phone"
                                required><br><br>
                        </div>

                        <div class="size col-l-6 col-m-6 col-s-12">
                            <label for="price">Property Rate:</label><br>
                            <input type="number" id="price" name="price" autocomplete="Property Rate" required><br><br>
                        </div>
                    </div>


                    <label for="description">Description:</label><br>
                    <input type="tel" id="description" name="description" autocomplete="description" required><br><br>

                    <div class="media-container">
                        <div class="size col-l-6 col-m-6 col-s-12">
                            <label for="images">Images of the Property:</label><br>
                            <input type="file" name="images[]" id="images" multiple required><br><br>
                        </div>

                        <div class="size col-l-6 col-m-6 col-s-12">
                            <label for="videos">Videos of the Property:</label><br>
                            <input type="file" name="videos[]" id="videos" multiple required><br><br>
                        </div>
                    </div>

                    <div class="submit">
                        <input type="submit" name="submit" value="Submit">
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>

</html>