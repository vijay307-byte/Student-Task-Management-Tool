<?php
require_once 'db_connect.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $message = 'Username and password are required.';
    } else {

        $stmt = $conn->prepare(
            "SELECT id, username, password FROM users WHERE username = ?"
        );

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {

                $_SESSION['user_id']  = $user['id'];
                $_SESSION['username'] = $user['username'];

                header("Location: task.php");
                exit();
            }
        }

        $message = "Invalid username or password.";
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>
<body>




    

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


    <div class="hero border border-light rounded-3 p-5 mb-5 flex-column justify-content-center w-50 m-auto" >
        <h1 class="text-center mb-4">Login</h1>
        


        <form method="POST" action="login.php" novalidate>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username" class="form-control" required autofocus />
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" required />
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
       <?php
            if (isset($_GET['signup']) && $_GET['signup'] === 'success') {
                echo '<div class="alert alert-success text-center">
                        Signup successful. Please log in.
                    </div>';
            }
        ?>

        </form>
        <p class="mt-3 text-center">Don't have an account? <a href="signup.php">Sign up here</a>.</p>
    </div>
</section>



 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    
</body>
</html>
           