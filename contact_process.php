<?php

if (isset($_POST['send'])) {
    $from = 'sotnikoff05@gmail.com'; 
    $subject = 'The following message was sent from the website';

    // validate inputs
    $name = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING));
    $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
    $message = trim(filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS));
    $validEmail = filter_var($email, FILTER_VALIDATE_EMAIL);

    if ($name && $validEmail && $message) {

        $email_message = "Fullname: $name\n";
        $email_message .= "Email address: $validEmail\n";
        $email_message .= "Message:\n$message\n";

        $headers = "From: $validEmail\r\n";
        $headers .= "Reply-To: $validEmail\r\n";
        $headers .= "Content-Type: text/plain; charset=utf-8\r\n";

        $success = mail($from, $subject, $email_message, $headers);

        if ($success) {
            echo "Thank you for contacting us. We will get back to you shortly.";
        } else {
            echo "Sorry, something went wrong. Please try again later.";
        }
    } else {
        echo "Please fill out all fields and provide a valid email address.";
    }

    header('Location: contacts.php');
}
?>
