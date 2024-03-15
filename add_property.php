<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>POST YOUR PROPERTY FOR FREE</title>
  <link rel="stylesheet" href="property.css" />
</head>

<body>
  <header>
    <!-- <iframe src="header.html" width="100%" height="100px" frameborder="0"></iframe> -->
    <?php include "header.html" ?>
  </header>
  <main>
    <div class="upper">
      <h6>ARE YOU AN OWNER</h6>
      <h3>Sell or Rent your property <br />fast with Proptokart</h3>
    </div>
    <div class="lower">
      <div class="left col-l-5 col-m-5 col-s-12">
        <img src="Proptokart\register_property.jpg" alt="" srcset="" />
      </div>
      <div class="right col-l-7 col-m-7 col-s-12">
        <form action="" method="post" enctype="multipart/form-data">
          <label for="name">Name:</label><br />
          <input type="text" id="name" name="name" /><br /><br />

          <label for="place">Place:</label><br />
          <input type="text" id="place" name="place" /><br /><br />

          <label for="price">Price:</label><br />
          <input type="text" id="price" name="price" /><br /><br />

          <div class="media-container">
            <div class="size col-l-6 col-m-6 col-s-12">
              <label for="images">Images:</label>
              <input type="file" name="images[]" id="images" multiple />
            </div>

            <div class="size col-l-6 col-m-6 col-s-12">
            <label for="videos">Videos:</label>
            <input type="file" name="videos[]" id="videos" multiple />
          </div>
          </div>

          <div class="submit">
            <input type="submit" name="submit" value="Submit" />
          </div>
        </form>
      </div>
    </div>
  </main>

  <?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "office";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
      die("Connection failed: " .
        $conn->connect_error);
    }
    $name = $_POST["name"] ?? "";
    $place =
      $_POST["place"] ?? "";
    $price = $_POST["price"] ?? "";
    if (
      !empty($name) &&
      !empty($place) && !empty($price)
    ) {
      $sql = "INSERT INTO add_property (name,
    place, price) VALUES (?, ?, ?)";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("sss", $name, $place, $price);
      $stmt->execute();
      $property_id = $stmt->insert_id;
      $stmt->close();
      foreach ($_FILES['images']['tmp_name'] as $key => $image_tmp_name) {
        $image_data =
          file_get_contents($image_tmp_name);
        $sql = "INSERT INTO property_images
    (property_id, image_name, image_data) VALUES (?, ?, ?)";
        $stmt =
          $conn->prepare($sql);
        $stmt->bind_param(
          "iss",
          $property_id,
          $_FILES['images']['name'][$key],
          $image_data
        );
        $stmt->send_long_data(
          2,
          $image_data
        );
        $stmt->execute();
        $stmt->close();
      }
      foreach ($_FILES['videos']['tmp_name'] as $key => $video_tmp_name) {
        $video_data =
          file_get_contents($video_tmp_name);
        $sql = "INSERT INTO property_videos
    (property_id, video_name, video_data) VALUES (?, ?, ?)";
        $stmt =
          $conn->prepare($sql);
        $stmt->bind_param(
          "iss",
          $property_id,
          $_FILES['videos']['name'][$key],
          $video_data
        );
        $stmt->send_long_data(
          2,
          $video_data
        );
        $stmt->execute();
        $stmt->close();
      }

      echo "<script>alert('Form submitted successfully!');</script>";
    } else {
      echo "<script>alert('Please fill in all required fields.');</script>";
    }
    $conn->close();
  } ?>
</body>

</html>