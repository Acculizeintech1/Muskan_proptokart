<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    include "connection.php";
    
    if (isset ($_POST['submit_del'])) {
        // Assuming $conn is your database connection object
        $username = $_COOKIE['username'];
        $id = $_POST['delete_id'];

        // Prepare and execute SQL statement
        $sql = "DELETE FROM {$username} WHERE id = ?";
        $stmt = $conn->prepare($sql);

        // Check if prepare() succeeded
        if ($stmt === false) {
            // Error handling
            echo "Error preparing SQL statement: " . $conn->error;
            echo "<script>alert('Error preparing SQL statement: '); window.location.href = 'modify_data.php';</script>";
        } else {
            // Bind parameters
            $stmt->bind_param("i", $id); // Assuming ID is an integer

            // Execute the statement
            $stmt->execute();

            // Check if the deletion was successful
            if ($stmt->affected_rows > 0) {
                echo "Row with ID $id deleted successfully.";
                echo "<script>alert('Row with ID $id deleted successfully.'); window.location.href = 'modify_data.php';</script>";
            } else {
                echo "Error deleting row or no rows matched the provided ID.";
                echo "<script>alert('Error deleting row or no rows matched the provided ID.'); window.location.href = 'modify_data.php';</script>";
            }

            // Close statement
            $stmt->close();
        }
    } elseif (isset ($_POST['submit_update'])) {

        // Assuming $conn is your database connection object
        $username = $_COOKIE['username'];
        $id = $_POST['update_id'];
        $Attribute = $_POST['Attribute'];
        $new = $_POST['New'];

        // Prepare and execute SQL statement
        $sql = "UPDATE {$username} set $Attribute = '$new' WHERE id = ?";
        $stmt = $conn->prepare($sql);

        // Check if prepare() succeeded
        if ($stmt === false) {
            // Error handling
            echo "Error preparing SQL statement: " . $conn->error;
            // echo "<script>alert('Error preparing SQL statement: '); window.location.href = 'modify_data.php';</script>";
        } else {
            // Bind parameters
            $stmt->bind_param("i", $id); // Assuming ID is an integer

            // Execute the statement
            $stmt->execute();

            // Check if the deletion was successful
            if ($stmt->affected_rows > 0) {
                echo "Row with ID $id Updated successfully.";
                echo "<script>alert('Row with ID $id Updated successfully.'); window.location.href = 'modify_data.php';</script>";
            } else {
                echo "Error updating row or no rows matched the provided ID.";
                echo "<script>alert('Error updating row or no rows matched the provided ID.'); window.location.href = 'modify_data.php';</script>";
            }

            // Close statement
            $stmt->close();
        }

    } elseif (isset ($_POST['submit_add'])) {
        $username = $_COOKIE['username'];
        $sql = "INSERT INTO $username (address, owner_name, owner_email, owner_phone, price, description) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $_POST['Address'], $_POST['name'], $_POST['email'], $_POST['phone'], $_POST['price'], $_POST['description']);
        $stmt->execute();
        $id = $stmt->insert_id;
        $stmt->close();

        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            $image_data = file_get_contents($tmp_name);
            $image_name = $_FILES['images']['name'][$key];
            $image_type = $_FILES['images']['type'][$key];

            $sql = "INSERT INTO {$username}_image (id, image_name, image_data, image_type) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $null = NULL;
            $stmt->bind_param("isbs", $id, $image_name, $null, $image_type);
            $stmt->send_long_data(2, $image_data);
            $stmt->execute();
            $stmt->close();
        }

        foreach ($_FILES['videos']['tmp_name'] as $key => $tmp_name) {
            $video_data = file_get_contents($tmp_name);
            $video_name = $_FILES['videos']['name'][$key];
            $video_type = $_FILES['videos']['type'][$key];

            $sql = "INSERT INTO {$username}_video (id, video_name, video_data, video_type) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $null = NULL;
            $stmt->bind_param("isbs", $id, $video_name, $null, $video_type);
            $stmt->send_long_data(2, $video_data);
            $stmt->execute();
            $stmt->close();
        }


        $conn->close();
        echo "<script>alert('Data Added Successfully'); window.location.href = 'modify_data.php';</script>";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify Data</title>
    <link rel="stylesheet" href="css\property.css">
    <style>
        .form-section {
            display: none;
            width: -webkit-fill-available;
            margin-top: -3%;
        }

        .active {
            display: block;
        }

        body {
            background-color: #264553;
            height: auto;
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
            width: -webkit-fill-available;
        }

        .selection-buttons button {
            padding: 1% 3%;
            margin: 1%;
            /* border: none; */
            background-color: black;
            border-radius: 9px;
            border: 1px solid black;
            font-family: monospace;
            font-size: 144%;
        }

        .selection-buttons button:hover {
            background-color: rgb(0 0 0 / 70%);
        }

        .form-container {
            margin: 2% auto;
            font-family: cursive;
            color: black;
            background-color: white;
            border-radius: 45px;
            /* padding: 2% 2%; */
            padding: 2% 2% 0% 2%;
            width: 90%;
        }

        .form-container .lower {
            text-align: justify;
            align-items: center;
        }

        .form-container .lower .media-container {
            width: 98%;
        }

        .form-section .form-container .lower .submit {
            text-align: center;
        }

        .form-section .form-container .upper h3 {

            margin: -15px auto 10px auto;
        }

        .form-section .form-container .lower .right select {
            width: 50%;
            /* margin: 0 0%; */
            padding: 1% 3%;
            background-color: lightgray;
            border: none;
            border-radius: 20px;
        }

        h1 {
            margin: 0px;
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
            <button class="section-btn" data-section="delete">Delete Data</button>
            <button class="section-btn" data-section="update">Update Data</button>
            <button class="section-btn" data-section="add">Add Data</button>
        </div>

        <div class="form-section delete active">
            <div class="form-container">
                <h6>
                    <?php if (isset ($_COOKIE['username'])) {
                        $username = $_COOKIE['username'];
                        // echo "HELLO, $username!";
                    } else {
                        // echo "Welcome!";
                    } ?>
                </h6>
                <h3>Delete Data from your Account
                    <?php echo "$username!"; ?>
                </h3>
                <div class="lower">
                    <div class="left col-l-6 col-m-6 col-s-12">
                        <img src="Proptokart\register_property.jpg" alt="" srcset="">
                    </div>
                    <div class="right col-l-6 col-m-6 col-s-12">
                        <form method="post" enctype="multipart/form-data">
                            <label for="delete_id">Owner's ID:</label><br>
                            <input type="text" id="delete_id" name="delete_id" autocomplete="Enter Owner's ID"
                                required><br><br>
                            <div class="submit">
                                <input type="submit" name="submit_del" value="Delete">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-section update">
            <div class="form-container">
                <h6>
                    <?php if (isset ($_COOKIE['username'])) {
                        $username = $_COOKIE['username'];
                        // echo "HELLO, $username!";
                    } else {
                        // echo "Welcome!";
                    } ?>
                </h6>
                <h3>Updata Data of your Account
                    <?php echo "$username!"; ?>
                </h3>
                <div class="lower">
                    <div class="left col-l-6 col-m-6 col-s-12">
                        <img src="Proptokart\register_property.jpg" alt="" srcset="">
                    </div>
                    <div class="right col-l-6 col-m-6 col-s-12">
                        <form method="post" enctype="multipart/form-data">
                            <label for="update_id">Owner's ID:</label><br>
                            <input type="text" id="update_id" name="update_id" autocomplete="Enter Owner's ID"
                                required><br><br>

                            <label for="Attribute">Column to be Updated:</label>
                            <Select id="Attribute" name="Attribute">
                                <option value="address">Address</option>
                                <option value="owner_name">Name</option>
                                <option value="owner_email">Email</option>
                                <option value="owner_phone">Phone</option>
                                <option value="price">Price</option>
                                <option value="description">Description</option>
                            </Select><br><br>

                            <label for="New">Updated Data</label><br>
                            <input type="text" id="New" name="New" autocomplete="Updated Value" required><br><br>

                            <div class="submit">
                                <input type="submit" name="submit_update" value="Update">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-section add">
            <div class="form-container">
                <h6>
                    <?php if (isset ($_COOKIE['username'])) {
                        $username = $_COOKIE['username'];
                        // echo "HELLO, $username!";
                    } else {
                        // echo "Welcome!";
                    } ?>
                </h6>
                <h3>Add New Data to your Account
                    <?php echo "$username!"; ?>
                </h3>
                <div class="lower">
                    <div class="right col-l-12 col-m-12 col-s-12">
                        <form method="post" enctype="multipart/form-data">
                            <label for="Address">Address:</label><br>
                            <input type="text" id="Address" name="Address" autocomplete="Address" required><br><br>

                            <div class="media-container">
                                <div class="size col-l-6 col-m-6 col-s-12">
                                    <label for="name">Owner Name:</label><br>
                                    <input type="text" id="name" name="name" autocomplete="name" required><br><br>
                                </div>

                                <div class="size col-l-6 col-m-6 col-s-12">
                                    <label for="email">Owner Email:</label><br>
                                    <input type="email" id="email" name="email" autocomplete="email" required><br><br>
                                </div>
                            </div>

                            <div class="media-container">
                                <div class="size col-l-6 col-m-6 col-s-12">
                                    <label for="phone">Owner phone:</label><br>
                                    <input type="tel" id="phone" name="phone" autocomplete="phone" required><br><br>
                                </div>

                                <div class="size col-l-6 col-m-6 col-s-12">
                                    <label for="price">Property Rate:</label><br>
                                    <input type="number" id="price" name="price" autocomplete="Property Rate"
                                        required><br><br>
                                </div>
                            </div>


                            <label for="description">Description:</label><br>
                            <input type="tel" id="description" name="description" autocomplete="description"
                                required><br><br>

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
                                <input type="submit" name="submit_add" value="Submit">
                            </div>
                        </form>
                    </div>
                </div>
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
    </script>
</body>

</html>