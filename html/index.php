<?php
session_start();
include 'C:/xampp/htdocs/ToDoList/database/connection.php';

$title = "";
$id = 0;
$update = false;
if (isset($_POST['submit'])) {
    if(empty($_POST['title'])) {
        $errors = "You must fill in the task";
    } else {
        $title = $_POST['title'];
        $sql = "INSERT INTO tasks (title) VALUES('$title')";
    
        $result = mysqli_query($con, $sql);
        $_SESSION['message'] = "Record has been saved!";
        $_SESSION['msg_type'] = "Success";
    
        if ($result) {
            header("location: index.php");
        }
    }
}

if (isset($_GET['del_task'])) {
    $id = $_GET['del_task'];

    $sql = "DELETE FROM tasks WHERE id=$id";
    $result = mysqli_query($con, $sql);

    $_SESSION['message'] = "Record has been deleted!";
    $_SESSION['msg_type'] = "Danger";

    if ($result) {
        header("location: index.php");
    }
}

if(isset($_GET['edit_task'])) {
    $id = $_GET['edit_task'];
    $update = true;
    $sql = "SELECT * FROM tasks WHERE id=" . $id;

    $result = mysqli_query($con, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $title = $row['title'];
    }
}

if(isset($_POST['update'])) {
    $id=$_POST['id'];
    $title=$_POST['title'];
    $sql="UPDATE tasks SET title='$title' WHERE id=$id";
    $result=mysqli_query($con, $sql);
    if($result){
        header("location: index.php");
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>ToDo List Application PHP and MySQL</title>
    <link rel="stylesheet" type="text/css" href="/ToDoList/css/style.css">
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
</head>

<body>
    <div class="wrapper">
        <div class="heading">
            <h2>To Do List Application</h2>
        </div>

        <form method="post" action="index.php" class="input_form">
            <?php if (isset($errors)) { ?>
                <p><?php echo $errors; ?></p>
            <?php } ?>
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="text" name="title" id="title" class="task_input" 
                    value="<?php echo $title; ?>" placeholder="Enter task to add in To Do List">
            <?php
            if($update ==true):
            ?>
                <button type="submit" name="update" id="add_btn" class="add_btn">Update</button>
            <?php else : ?>
                <button type="submit" name="submit" id="add_btn" class="add_btn">Add Task</button>
            <?php endif; ?>
            
        </form>

        <hr>

        <table>
            <thead>
                <tr>
                    <th style="width: 70px">Sr. No</th>
                    <th>Tasks</th>
                    <th style="width: 200px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php

                $sql = "SELECT * FROM tasks";
                $result = mysqli_query($con, $sql);

                if ($result) {
                    $i = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        $id = $row['id'];
                        $title = $row['title'];

                ?>
                        <tr>
                            <td><?php echo $i ?></td>
                            <td><?php echo $title  ?></td>
                            <td class="edit_delete">
                                <a class="btn-success" href="index.php?edit_task=<?php echo $id ?>"> <i class='fas fa-pencil-alt' style="margin-right: 10px;"></i>Edit</a>
                                
                                <a class="btn-danger" href="index.php?del_task=<?php echo $id ?>"> <i class='fas fa-trash-alt' style="margin-right: 10px;"></i>Delete</a>
                            </td>

                        </tr>

                <?php

                        $i++;
                    }
                }
                ?>


            </tbody>
        </table>
    </div>

</body>

</html>