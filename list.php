<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Listings</title>
    <link rel="stylesheet" href="css\property.css" />
    <style>
        #loading {
            display: none;
            text-align: center;
            margin-top: 20px; /* Adjust as needed */
        }
    </style>
    <script>
        function showMedia(userId) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        document.getElementById("mediaContainer").innerHTML = xhr.responseText;
                        document.getElementById("loading").style.display = "none";
                    } else {
                        document.getElementById("mediaContainer").innerHTML = "Error loading media.";
                        document.getElementById("loading").style.display = "none";
                    }
                }
            };
            xhr.open("GET", "fetch_media.php?userId=" + userId, true);
            xhr.send();
            document.getElementById("loading").style.display = "block";
        }
    </script>
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
                    <th>Name</th>
                    <th>Address of the Property</th>
                    <th>Contact Number</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Your PHP code to fetch property listings
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "office";

                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT id, name, place, price FROM add_property";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . $row["name"] . "</td>";
                        echo "<td>" . $row["place"] . "</td>";
                        echo "<td>" . $row["price"] . "</td>";
                        echo "<td><button onclick='showMedia(" . $row["id"] . ")'>Show Media</button></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No users found</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
        <div id="loading">Loading...</div>
        <div id="mediaContainer"></div>
    </main>
</body>
</html>
