<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "office";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die ("Connection failed: " . $conn->connect_error);
}

// Retrieve user ID from the AJAX request
$userId = $_GET["userId"];

// Fetch images and videos associated with the user ID from the database
$sqlImages = "SELECT image_data, image_type FROM property_images WHERE property_id = ?";
$stmtImages = $conn->prepare($sqlImages);
if (!$stmtImages) {
    die ("SQL Error: " . $conn->error);
}
$stmtImages->bind_param("i", $userId);
$stmtImages->execute();
$resultImages = $stmtImages->get_result();

$sqlVideos = "SELECT video_data, video_type FROM property_videos WHERE property_id = ?";
$stmtVideos = $conn->prepare($sqlVideos);
if (!$stmtVideos) {
    die ("SQL Error: " . $conn->error);
}
$stmtVideos->bind_param("i", $userId);
$stmtVideos->execute();
$resultVideos = $stmtVideos->get_result();

// Display images
echo "<h2>Images of the Property</h2>";
echo "<div style='display: flex; flex-wrap: wrap;''>";
if ($resultImages->num_rows > 0) {
    while ($row = $resultImages->fetch_assoc()) {
        $base64_image_data = base64_encode($row['image_data']);
        echo "<img src='data:image/" . $row['image_type'] . ";base64," . $base64_image_data . "'height= '400px' width = '400px'alt='Property Image'><br>";
    }
} else {
    echo "No images found";
}
echo "</div>";

// Display videos
echo "<h2>Videos of the Property</h2>";
echo "<div style='display: flex; flex-wrap: wrap;''>";
if ($resultVideos->num_rows > 0) {
    while ($row = $resultVideos->fetch_assoc()) {
        // Retrieve video data and type
        $videoData = $row['video_data'];
        $videoType = $row['video_type'];

        // Output the video if data is available
        if (!empty ($videoData)) {
            // Encode the video data as base64
            $base64_video_data = base64_encode($videoData);

            // Output the video with proper MIME type and base64-encoded data
            echo "<video width='320' height='240' controls>";
            echo "<source src='data:video/$videoType;base64,$base64_video_data' type='video/$videoType'>";
            echo "Your browser does not support the video tag.";
            echo "</video><br>";
        } else {
            echo "No videos found";
        }
    }
} else {
    echo "No videos found";
}
echo "</div>";



// Close the database connection
$stmtImages->close();
$stmtVideos->close();
$conn->close();
?>