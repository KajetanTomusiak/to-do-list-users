<?php

session_start();
require_once "db.php";

// Funkcja do uwierzytelniania użytkownika
function authenticate($username, $password) {
    global $dbcon;

    // Sprawdzenie, czy użytkownik istnieje w bazie danych
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $dbcon->query($query);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Sprawdzenie hasła
        if (authenticate($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            return true;
        }
    }

    return false;
}

// Sprawdzenie, czy użytkownik jest zalogowany
function isUserLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Przekierowanie do strony logowania, jeśli użytkownik nie jest zalogowany
function redirectIfNotLoggedIn() {
    if (!isUserLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

// Wylogowanie użytkownika
function logoutUser() {
    session_destroy();
    header("Location: login.php");
    exit();
}
