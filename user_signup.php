<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Include database connection
  include "connection.php";

  // Create the User_data table if not exists
  $sql_create_main_table = "CREATE TABLE IF NOT EXISTS User_data (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        username VARCHAR(50) UNIQUE,
        email VARCHAR(50) UNIQUE,
        phone VARCHAR(15),
        address VARCHAR(255),
        password VARCHAR(255) NOT NULL
    )";

  if ($conn->query($sql_create_main_table) === TRUE) {
    echo "Table User_data created successfully.<br>";
  } else {
    echo "Error creating table User_data: " . $conn->error . "<br>";
  }

  // Retrieve and sanitize user input
  $name = $conn->real_escape_string($_POST['name']);
  $username = $conn->real_escape_string($_POST['username']);
  $email = $conn->real_escape_string($_POST['email']);
  $phone = $conn->real_escape_string($_POST['phone']);
  $address = $conn->real_escape_string($_POST['address']);
  $password = password_hash($conn->real_escape_string($_POST['password']), PASSWORD_DEFAULT);

  // Check if the username or email already exists
  $sql_check_user_entry = "SELECT COUNT(*) as count FROM User_data WHERE username = ? OR email = ?";
  $stmt_check_user = $conn->prepare($sql_check_user_entry);
  $stmt_check_user->bind_param("ss", $username, $email);
  $stmt_check_user->execute();
  $result = $stmt_check_user->get_result();
  $row = $result->fetch_assoc();
  $stmt_check_user->close();

  if ($row['count'] != 0) {
    echo "<script>alert('$username or $email already exists. Please choose another username or email.'); window.location.href = 'user_signup.php';</script>";
  } else {
    // Insert user record into the User_data table
    $sql_user = "INSERT INTO User_data (name, username, email, phone, address, password) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_user = $conn->prepare($sql_user);
    $stmt_user->bind_param("ssssss", $name, $username, $email, $phone, $address, $password);

    if ($stmt_user->execute()) {
      $user_id = $stmt_user->insert_id;
      $stmt_user->close();
      // Redirect to login form after successful account creation
      echo "<script>alert('Account created successfully for $username. You can now log in.'); window.location.href = 'user_login.php';</script>";
    } else {
      echo "<script>alert('Error creating account. Please try again later.'); window.location.href = 'user_signup.php';</script>";
    }
  }

  // Close database connection
  $conn->close();
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create your Account to get Better Property Recommendations</title>
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
      margin: -43px auto;
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

    .form-container form input[type="checkbox"] {
      width: fit-content;
    }

    h2 {
      font-size: 310%;
      font-weight: bold;
      margin: 0 0 10% 0;
    }

    input,
    textarea {
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

      h2 {
        font-size: 174%;
        font-weight: bold;
        margin: 31px 0 10% 0;
      }
    }

    @media only screen and (min-width: 601px) {
      .form-container {
        width: 65%;
      }

      .form-container input,
      textarea {
        width: 68%;
      }

      h2 {
        font-size: 262%;
      }
    }

    @media only screen and (min-width: 768px) {
      .form-container {
        width: 45%;
      }

      .form-container input,
      textarea {
        width: 77%;
        font-size: 127%;
        text-align: center;
        margin-top: 2%;
      }

      .form-container label {
        font-size: 175%;
      }

      h2 {
        font-size: 233%;
        margin: 5% auto;
      }
    }

    @media only screen and (min-width: 1200px) {
      .form-container {
        width: 30%;
      }

      .form-container input,
      textarea {
        width: 60%;
        font-size: 82%;
        text-align: center;
        margin-top: 2%;
        margin-left: 3%;
      }

      .form-container form {
        margin-top: 4%;
      }

      .form-container label {
        font-size: 120%;
      }

      h2 {
        font-size: 233%;
        margin: 0% auto;
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
      <h2>Create Account</h2>
      <form method="post" enctype="multipart/form-data">

        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" placeholder="Name" required><br><br>

        <label for="username">UserName</label>
        <input type="text" id="username" name="username" placeholder="userName" required><br><br>

        <label for="email">Email Id : </label>
        <input type="email" id="email" name="email" placeholder="UserName@gmail.com" required><br><br>

        <label for="phone">Phone No.:</label>
        <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required><br><br>

        <label for="address">Address.:</label>
        <textarea name="address" id="address" cols="40" rows="1"></textarea><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Password" required><br><br>

        <input type="checkbox" id="showPassword">
        <label for="showPassword">Show Password</label><br><br>

        <button type="submit">Submit</button>
      </form>
    </div>

  </main>
  <script src="Script.js"></script>
</body>

</html>