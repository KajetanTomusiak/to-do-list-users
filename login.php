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

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hashedPassword = $row['password'];

            if (password_verify($password, $hashedPassword)) {
                // Użytkownik uwierzytelniony, przekieruj na stronę główną
                $_SESSION['user_id'] = $row['id'];
                header("Location: index.php");
                exit();
            } else {
                $_SESSION['login_error'] = "Błędny login lub hasło.";
            }
        } else {
            $_SESSION['login_error'] = "Taki użytkownik nie istnieje.";
        }
    }
?>


<div class="container-fluid d-flex justify-content-center align-items-center vh-100">
    <form action="login.php" method="post">
        <?php
        if (isset($_SESSION['login_error'])) {
            echo '<div class="alert alert-danger text-center" role="alert">' . $_SESSION['login_error'] . '</div>';
            unset($_SESSION['login_error']);
        }
        ?>
        <p class="text-center display-4 pb-5">Logowanie</p>
        <div class="form-group">
            <input class="form-control form-control-lg" type="text" name="username" placeholder="Nazwa użytkownika">
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
            <input class="btn btn-success btn-block" type="submit" name="login" value="Zaloguj"><br>
        </div>

        <a class="d-flex justify-content-center text-center" href="register.php">Rejestracja</a>
    </form>
</div>

<script src="showPassword.js"></script>