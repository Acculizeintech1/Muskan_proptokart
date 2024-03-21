<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    include "connection.php" ;
    
    $sql_add_property = "CREATE TABLE IF NOT EXISTS add_property (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        place VARCHAR(255) NOT NULL,
        phone VARCHAR(20) NOT NULL,
        email VARCHAR(255) NOT NULL
    )";
    
    // Execute the query to create the add_property table
    if ($conn->query($sql_add_property) === TRUE) {
        echo "Table add_property created successfully.<br>";
    } else {
        echo "Error creating table add_property: " . $conn->error . "<br>";
    }
    
    // SQL query to create the property_images table
    $sql_property_images = "CREATE TABLE IF NOT EXISTS property_images (
        id INT AUTO_INCREMENT PRIMARY KEY,
        property_id INT,
        image_name VARCHAR(255) NOT NULL,
        image_data LONGBLOB,
        image_type VARCHAR(255),
        FOREIGN KEY (property_id) REFERENCES add_property(id) ON DELETE CASCADE
    )";
    
    // Execute the query to create the property_images table
    if ($conn->query($sql_property_images) === TRUE) {
        echo "Table property_images created successfully.<br>";
    } else {
        echo "Error creating table property_images: " . $conn->error . "<br>";
    }
    
    // SQL query to create the property_videos table
    $sql_property_videos = "CREATE TABLE IF NOT EXISTS property_videos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        property_id INT,
        video_name VARCHAR(255) NOT NULL,
        video_data LONGBLOB,
        video_type VARCHAR(255),
        FOREIGN KEY (property_id) REFERENCES add_property(id) ON DELETE CASCADE
    )";
    
    // Execute the query to create the property_videos table
    if ($conn->query($sql_property_videos) === TRUE) {
        echo "Table property_videos created successfully.<br>";
    } else {
        echo "Error creating table property_videos: " . $conn->error . "<br>";
    }

    
    $sql = "INSERT INTO add_property (name, place, phone,email) VALUES (?, ?, ? ,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $_POST['name'], $_POST['Address'], $_POST['phone'],$_POST['email']);
    $stmt->execute();
    $property_id = $stmt->insert_id;
    $stmt->close();

    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
        $image_data = file_get_contents($tmp_name);
        $image_name = $_FILES['images']['name'][$key];
        $image_type = $_FILES['images']['type'][$key];

        $sql = "INSERT INTO property_images (property_id, image_name, image_data, image_type) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $null = NULL;
        $stmt->bind_param("isbs", $property_id, $image_name, $null, $image_type);
        $stmt->send_long_data(2, $image_data);
        $stmt->execute();
        $stmt->close();
    }

    foreach ($_FILES['videos']['tmp_name'] as $key => $tmp_name) {
        $video_data = file_get_contents($tmp_name);
        $video_name = $_FILES['videos']['name'][$key];
        $video_type = $_FILES['videos']['type'][$key];

        $sql = "INSERT INTO property_videos (property_id, video_name, video_data, video_type) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $null = NULL;
        $stmt->bind_param("isbs", $property_id, $video_name, $null, $video_type);
        $stmt->send_long_data(2, $video_data);
        $stmt->execute();
        $stmt->close();
    }

    $conn->close();
    echo "<script>alert('Property Added Successfully'); window.location.href = 'index.html';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POST YOUR PROPERTY FOR FREE</title>
    <link rel="stylesheet" href="css\property.css">
</head>

<body>
    <header>
        <?php include "header.html" ?>
    </header>
    <main>
        <div class="upper">
            <h6>ARE YOU AN OWNER</h6>
            <h3>Sell or Rent your property <br>fast with Proptokart</h3>
        </div>
        <div class="lower">
            <div class="left col-l-5 col-m-5 col-s-12">
                <img src="Proptokart\register_property.jpg" alt="">
            </div>
            <div class="right col-l-7 col-m-7 col-s-12">
                <form method="post" enctype="multipart/form-data">
                    <label for="name">Name:</label><br>
                    <input type="text" id="name" name="name" autocomplete="name" required><br><br>

                    <label for="Address">Address of the property to sell/ Rent:</label><br>
                    <input type="text" id="Address" name="Address" autocomplete="Address" required><br><br>

                    <label for="phone">Contact Number:</label><br>
                    <input type="tel" id="phone" name="phone" autocomplete="phone" required><br><br>

                    <label for="email">Email:</label><br>
                    <input type="email" id="email" name="email" autocomplete="email" required><br><br>

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