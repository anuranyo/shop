<?php
// login_process.php
require_once 'php/users.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = "Email and password are required!";
    } else {
        $user = new User();
        $authenticatedUser = $user->authenticateUser($email, $password);

        if ($authenticatedUser) {
            $_SESSION['user_id'] = $authenticatedUser['id'];
            $_SESSION['username'] = $authenticatedUser['username'];
            header('Location: index.php');
            exit;
        } else {
            $error = "Invalid email or password!";
        }
    }
}

if (isset($error)) {
    header('Location: login.php?error=' . urlencode($error));
    exit;
}
?>
