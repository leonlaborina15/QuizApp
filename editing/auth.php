<?php
session_start();
include 'db.php';

function sanitizeInput($data)
{
    return htmlspecialchars(strip_tags(trim($data)));
}

if (isset($_POST['register'])) {
    $username = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: signup.php");
        exit();
    }

    if ($password !== $confirmPassword) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: signup.php");
        exit();
    }

    if (strlen($password) < 8 || $password[0] !== strtoupper($password[0])) {
        $_SESSION['error'] = "Password must be at least 8 characters and start with an uppercase letter.";
        header("Location: signup.php");
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("SELECT user_id FROM Users WHERE email = ? OR username = ?");
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $_SESSION['error'] = "Email or username already exists.";
        $stmt->close();
        header("Location: signup.php");
        exit();
    }
    $stmt->close();

    $stmt = $conn->prepare("INSERT INTO Users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashedPassword);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Registration successful. Please login.";
        $stmt->close();
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['error'] = "Failed to register. Please try again.";
        $stmt->close();
        header("Location: signup.php");
        exit();
    }
}

if (isset($_POST['login'])) {
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Email and password are required.";
        header("Location: login.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT user_id, password FROM Users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($user_id, $hash);
    if ($stmt->fetch() && password_verify($password, $hash)) {
        // Login successful
        $_SESSION['user_id'] = $user_id;
        $stmt->close();
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['error'] = "Invalid email or password.";
        $stmt->close();
        header("Location: login.php");
        exit();
    }
}
?>