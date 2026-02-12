<?php

require_once 'db_connect.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* ===============================
   HANDLE TASK INSERT
   =============================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $task     = trim($_POST['task'] ?? '');
    $desc     = trim($_POST['desc'] ?? '');
    $deadline = $_POST['deadline'] ?? null;

    if ($task !== '') {
        $stmt = $conn->prepare(
            "INSERT INTO tasks (user_id, task, description, deadline)
             VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param("isss", $user_id,$task, $desc, $deadline);
        $stmt->execute();
        $stmt->close();
    }
}

/* ===============================
   FETCH USER TASKS
   =============================== */
$stmt = $conn->prepare(
    "SELECT task, description, deadline
     FROM tasks
     WHERE user_id = ?
     ORDER BY id DESC"
);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$tasks = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tasks</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>
    <?php  
        require_once 'db_connect.php';
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

    ?>


    
    
       

    <div class="navbar">
        <ul>
            <li><a href="index.php">Home</a></li>

            <?php if (!isset($_SESSION['user_id'])): ?>
                <!-- Guest View -->
                <li><a href="login.php">Login</a></li>
                <li><a href="signup.php">Signup</a></li>
            <?php else: ?>
                <!-- Authenticated View -->
                <li><a href="task.php">My Tasks</a></li>
                <li><a href="logout.php">Logout</a></li>
            <?php endif; ?>

        </ul>
    </div>

 <div class="container mt-5">
    <h2 class="mb-4">My Tasks</h2>

    <ul class="list-group">
        <?php if (!empty($tasks)): ?>

            <?php foreach ($tasks as $row): ?>
                <li class="list-group-item bg-dark text-white">
                    <?= htmlspecialchars($row['task'] ?? '') ?><br>

                    <?= htmlspecialchars($row['description'] ?? '') ?><br>

                    Deadline: <?= htmlspecialchars($row['deadline'] ?? '') ?>
                </li>

            <?php endforeach; ?>

        <?php else: ?>
            <li class="list-group-item">No tasks added yet.</li>
        <?php endif; ?>
    </ul>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous"></script>
</body>
</html>