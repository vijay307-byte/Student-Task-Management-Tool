
<?php
require_once 'db_connect.php';
session_start();

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $message = "Username and password are required.";
    } else {

  
        $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $message = "Username already exists. Please choose another.";
        } else {

            // 🔐 Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // 🧾 Insert user
            $stmt = $conn->prepare(
                "INSERT INTO users (username, password) VALUES (?, ?)"
            );
            $stmt->bind_param("ss", $username, $hashedPassword);

            if ($stmt->execute()) {
                // ✅ Redirect to login with success flag
                header("Location: login.php?signup=success");
                exit();
            } else {
                $message = "Signup failed. Please try again.";
            }

            $stmt->close();
        }

        $check->close();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
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

    
<section>

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
        <h1 class="text-center mb-4">Create an Account</h1>
    
        <form method="POST" action="signup.php" novalidate>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username" class="form-control" required autofocus />
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" required />
            </div>
            <button type="submit" class="btn btn-primary w-100">Sign Up</button>
        </form>
        <p class="mt-3 text-center">Already have an account? <a href="login.php">Login here</a>.</p>
    </div>
</section>



























    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>


</body>
</html>
