<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $to = "proptokart@gmail.com";
    $subject = $_POST["subject"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $message = $_POST["message"];
    
    $email_content = "Name: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Message:\n$message\n";
    
    if (mail($to, $subject, $email_content)) {
        echo "Email sent successfully!";
    } else {
        echo "Failed to send email.";
    }
}
?>
