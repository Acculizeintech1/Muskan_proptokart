<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin LogIn</title>
    <link rel="stylesheet" href="css\login.css">
</head>

<body>
    <header>
        <?php include "header.html" ?>
    </header>
    <main>
        <div class="form-container">
            <h2>Admin Section</h2>
            <form action="Admin_access.php" method="post" enctype="multipart/form-data">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" placeholder="UserName"><br><br>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Password"><br><br>
                <input type="checkbox" id="showPassword">
                <label for="showPassword">Show Password</label><br><br>
                <button type="submit">Login</button>
            </form>
        </div>
    </main>

    <script src="Script.js"></script>
</body>

</html>