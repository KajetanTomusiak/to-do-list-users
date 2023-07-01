<?php
session_start();
require_once "header.php";
require_once "db.php";
require_once "auth.php";
redirectIfNotLoggedIn();

$userId = $_SESSION['user_id'];

$username_query = "SELECT username FROM users WHERE id = $userId";
$username_result = $dbcon->query($username_query);
$username_row = $username_result->fetch_assoc();
$username = $username_row['username'];

$task_show_query = "SELECT * FROM tasks WHERE user_id = $userId"; // Poprawiono warunek zapytania
$result = $dbcon->query($task_show_query);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['addtask'])) {
        $taskName = $_POST['textfield'];

        // Dodawanie nowego zadania do bazy danych
        $add_task_query = "INSERT INTO tasks (user_id, task_name, added_time) VALUES ($userId, '$taskName', NOW())"; // Poprawiony zapis zapytania
        $add_task_result = $dbcon->query($add_task_query);

        if ($add_task_result) {
            // Przekierowanie po pomyślnym dodaniu zadania
            header("Location: index.php");
            exit();
        } else {
            // Obsługa błędu dodawania zadania
            echo "Wystąpił błąd podczas dodawania zadania.";
        }
    }
}
?>

<div class="container">
    <div class="row">
        <div class="pt-5 pb-5 col-8 m-auto">
            <h2 class="display-4 text-center">To Do List</h2>
            <div class="text-center">
                <p style="color: green">Zalogowano jako <b><?php echo $username; ?></b></p>
                <a class="btn btn-danger" href="logout.php">Wyloguj</a>
            </div>
            <form class="mt-4" action="index.php" method="post">
                <div class="form-group">
                    <input class="form-control form-control-lg" type="text" name="textfield" placeholder="Enter your task">
                </div>
                <div class="">
                    <input class="btn btn-success btn-block" type="submit" name="addtask" value="Add Task">
                </div>
            </form>
        </div>
    </div>
    <?php

    if (isset($_SESSION['delete_success'])) { ?>

        <div class="alert alert-warning text-dark mx-auto slide" role="alert" style="width:66%; display: block;">
            <?= $_SESSION['delete_success']; ?>
        </div>

    <?php
        unset($_SESSION['delete_success']);
    }

    ?>

    <?php

    if (isset($_SESSION['update_success'])) { ?>

        <div class="alert alert-warning text-dark mx-auto slide" role="alert" style="width:66%;">
            <?= $_SESSION['update_success']; ?>
        </div>

    <?php
        unset($_SESSION['update_success']);
    }

    ?>

    <table style="width:66%;" class="table table-hover table-borderless text-center mx-auto mt-3">
        <thead class="bg-light text-center">
            <tr>
                <th>#</th>
                <th>Task</th>
                <th>Date</th>
                <th>Time</th>
                <th>Actions</th>
            </tr>
        </thead>

        <?php
        if ($result->num_rows != 0) {
            $serial = 1;
            foreach ($result as $row) {
                $temp_date_time = (explode(' ', $row['added_time']));
                $date = $temp_date_time[0];
                $time = $temp_date_time[1];
        ?>

                <tr style="vertical-align: middle;">
                    <td><?= $serial++ ?></td>
                    <td style="word-wrap: break-word;min-width: 160px;max-width: 160px;"><?= $row['task_name'] ?></td>
                    <td><?= $date ?></td>
                    <td><?= $time ?></td>


                    <td>
                        <div class="btn-group">
                            <a class="btn btn-sm btn-success mr-1" href="update.php?id=<?php echo base64_encode($row['id']); ?>">
                                <i class="fa fa-fw fa-pencil"></i>
                            </a>
                            <a class="btn btn-sm btn-danger ml-1" href="delete.php?id=<?php echo base64_encode($row['id']); ?>">
                                <i class="fa fa-fw fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>

            <?php

            }
        } else {
            ?>
            <tr>
                <td colspan="20" class="text-center display-4 p-5" style="pointer-events: none !important;">No tasks</td>
            </tr>
        <?php
        }
        ?>

    </table>

</div>

</body>

</html>