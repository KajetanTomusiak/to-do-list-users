<?php
    session_start();
    require_once 'db.php';
    $id = base64_decode($_GET['id']);
    $data = "SELECT * FROM tasks WHERE id=$id";
    $data_from_db = $dbcon->query($data);
    $f_result = $data_from_db->fetch_assoc();

    if (isset($_POST['update'])) {
        $update_text = $_POST['update_text'];
        $update_query = "UPDATE tasks SET task_name='$update_text' WHERE id=$id";
        $update_date = $dbcon->query($update_query);

        if ($update_date) {
            $_SESSION['update_success'] = "Task updated successfully!";
        }

        header('location: index.php');
    }
?>


<?php
    require_once 'header.php';  
?>

<div class="container">
    <div class='row'>
        <div class="pt-5 pb-5 col-8 m-auto">
            <h2 class="display-4 mx-auto mt-2 text-center">Update Task</h2>
            <form class="mt-4" action="" method="post">
                <div class='form-group'>
                    <input class="form-control form-control-lg" type="text" name="update_text" value="<?= $f_result['task_name'] ?>">
                </div>
                <div class='form-group'>
                    <input class="btn btn-warning btn-block" type="submit" name="update" value="Update">
                </div>
            </form>
        </div>
    </div>
</div>

</body>

</html>