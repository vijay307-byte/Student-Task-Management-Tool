<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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


        <div class="container h-100 d-flex flex-column justify-content-center border rounded-3 p-5 mb-3" >
        
            <div class="my-3 text-center">
                <h1>Task Manager</h1>
            </div>

            <form action="task.php" method="post" >

                <div class="my-3">

                    <label for="task" class="form-label my-3">TASK</label>
                    <input type="text" class="form-control" id="task" name="task">

                    <label for="desc" class="form-label my-3">DESCRIPTION</label>
                    <input type="text" class="form-control" id="desc" name="desc">

                    <label for="deadline" class="form-label my-3">DEADLINE</label>
                    <input type="date" class="form-control" id="deadline" name="deadline">

                    <button type="submit" class="btn btn-primary my-3 w-100">ADD TASK</button>

                </div>

            </form>

        </div>

    


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    
</body>
</html>