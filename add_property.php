<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>POST YOUR PROPERTY FOR FREE</title>
  <link rel="stylesheet" href="property.css">
</head>
<body>
  <main>
  <div class="upper">
    <h6>ARE YOU AN OWNER</h6>
    <h3>Sell or Rent your property <br>fast with Proptokart</h3>
  </div>
  <div class="lower">
    <div class="left">
      <img src="Proptokart\register_property.jpg" alt="" srcset="">
    </div>
    <div class="right">
    <form action="" method="post" enctype="multipart/form-data">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name"><br><br>

        <label for="place">Place:</label><br>
        <input type="text" id="place" name="place"><br><br>

        <label for="price">Price:</label><br>
        <input type="text" id="price" name="price"><br><br>
        <div class="media-container">
        <label for="media" class="media" >Media (Images/Videos):</label><br>
        <input type="file" id="media" name="media[]" accept="image/*, video/*" multiple><br><br>
        </div>

        <input type="submit" name="submit" value="Submit">
    </form>
    </div>
  </div>
  </main>
  
  

  <?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $name = $_POST["name"];
    $place = $_POST["place"];
    $price = $_POST["price"];
    
    
    $directory = "C:/Users/HP/Desktop/FromProptokart/" . $name . "_" . time();
    if (!file_exists($directory)) {
        mkdir($directory, 0777, true);
    }
    
    
    $files = $_FILES["media"];
    foreach ($files["tmp_name"] as $key => $tmp_name) {
        $file_name = $files["name"][$key];
        $file_type = $files["type"][$key];
        $file_tmp = $files["tmp_name"][$key];
        $file_size = $files["size"][$key];
        $file_error = $files["error"][$key];
        
        
        $destination = $directory . "/" . $file_name;
        move_uploaded_file($file_tmp, $destination);
    }
    
    echo "<script>alert('Form submitted successfully!');</script>";
    } 
    // else {
    //     echo "<script>alert('Please fill in all required fields.');</script>";
    // }

?>
</body>
</html>
