<?php
// register_process.php
require_once 'php/users.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required!";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        $user = new User();
        if ($user->addUser($username, $email, $password)) {
            header('Location: login.php');
            exit;
        } else {
            $error = "Registration failed! Email might already be in use.";
        }
    }
}

if (isset($error)) {
    header('Location: register.php?error=' . urlencode($error));
    exit;
}
?>
