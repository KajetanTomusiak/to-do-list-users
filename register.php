<?php
session_start();
require_once "header.php";
require_once "db.php";
require_once "auth.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $dbcon->query($query);

    if ($result->num_rows == 0) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $insertQuery = "INSERT INTO users (username, password) VALUES ('$username', '$hashedPassword')";
        $dbcon->query($insertQuery);

        $_SESSION['registration_success'] = "Rejestracja zakończona pomyślnie. Możesz się teraz zalogować.";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['registration_error'] = "Taki użytkownik już istnieje.";
    }
}
?>

<div class="container-fluid d-flex justify-content-center align-items-center vh-100">
    <form action="register.php" method="post">
        <?php
        if (isset($_SESSION['registration_error'])) {
            echo '<div class="alert alert-danger text-center" role="alert">' . $_SESSION['registration_error'] . '</div>';
            unset($_SESSION['registration_error']);
        }
        ?>
        <p class="text-center display-4 pb-5">Rejestracja</p>
        <div class="form-group">
            <input class="form-control form-control-lg" type="text" name="username" placeholder="Nazwa użytkownika" required>
        </div>
        <div class="form-group">
            <div class="input-group">
                <input class="form-control form-control-lg" type="password" name="password" placeholder="Hasło" id="password" required>
                <button style="border: 1px solid #ced4da; color: #6c757d !important; background-color: transparent !important; box-shadow: none !important; border-top-left-radius: 0 !important; border-bottom-left-radius: 0 !important; border-left-width: 0px !important" class="btn btn-outline-secondary" type="button" id="togglePassword">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>
        <div class="">
            <input class="btn btn-success btn-block" type="submit" name="register" value="Zarejestruj" required><br>
        </div>
        <a class="d-flex justify-content-center text-center" href="login.php">Logowanie</a>
    </form>
</div>

<script src="showPassword.js"></script>