<?php
include("tdatabase.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDo List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" >
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body{
            background-color: black;
        }
        form {
            box-shadow: 2px 6px 100px white;
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        p{
            color: white;
        }
        .completed {
            text-decoration: line-through;
        }
        .list-group-item {
            background-color: lightyellow;
        }
        .completed.list-group-item {
            background-color: lightgreen;
        }
        .row {
            color: whitesmoke;
        }
        @media (max-width: 991px) {
            .sidebar{
                background-color: rgba(255,255,255,0.15);
                backdrop-filter:blur(10px);
                color: whitesmoke;
            }
        }
        @media (min-width: 1200px) {
            #gridCheck {
                font-size: 16px;
            }
        }
        @media (max-width: 1199.98px) {
            #gridCheck {
                font-size: 9px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark ">
        <div class="container">

            <p class="navbar-brand-fs-4">$@#</p>

            <button class="navbar-toggler  shadow-none border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>

            <div class="sidebar offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">

            <div class="offcanvas-header text-white border-bottom">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel">$@#</h5>
                <button type="button" class="btn-close-white shadow-none" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>

            <div class="offcanvas-body d-flex flex-column flex-lg-row p-4 p-lg-0">
                <ul class="navbar-nav justify-content-center justify-items-center fs-5 flex-grow-1 pe-3">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="todosite.php">Your List</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href=""></a>
                </li>
                </ul>
                <ul class="navbar-nav justify-content-between justify-items-between fs-5">
                <li class="nav-item">
                    <a class="nav-link active" href=""></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href=""></a>
                </li>
                </ul>
            </div>
            </div>
        </div>
        </nav>

    <section class="container my-2 w-50 text-color-black p-2">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"], ENT_QUOTES, 'UTF-8'); ?>" method="post" class="row g-3 p-3">
            <div>
                <input type="text" name="task" placeholder="Task" class="form-control" style="text-align: center;">
                <input type="submit" name="submit" value="Sent" class="btn btn-dark btn-sm float-end">
            </div>
        <div>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['task']) && !isset($_POST['delete']) && !isset($_POST['complete'])) {
                $task = filter_input(INPUT_POST, "task", FILTER_SANITIZE_SPECIAL_CHARS);

                if (!empty($task)) {
                    $sql = "INSERT INTO lists (task) VALUES (?)";
                    if ($stmt = mysqli_prepare($conn, $sql)) {
                        mysqli_stmt_bind_param($stmt, "s", $task);
                        try {
                            mysqli_stmt_execute($stmt);
                            echo "Task added successfully.<br>";
                        } catch (mysqli_sql_exception $e) {
                            echo "<br>Error: Task could not be added. It might already exist.<br>";
                        }
                        mysqli_stmt_close($stmt);
                    } else {
                        echo "<br>Error in preparing the statement.<br>";
                    }
                } else {
                    echo "<br>Please enter a task.<br>";
                }
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['complete'])) {
                $taskId = intval($_POST['complete']);
                $sql = "UPDATE lists SET completed = NOT completed WHERE id = ?";
                if ($stmt = mysqli_prepare($conn, $sql)) {
                    mysqli_stmt_bind_param($stmt, "i", $taskId);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);
                }
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
                $taskId = intval($_POST['delete']);
                $sql = "DELETE FROM lists WHERE id = ?";
                if ($stmt = mysqli_prepare($conn, $sql)) {
                    mysqli_stmt_bind_param($stmt, "i", $taskId);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);
                }
            }

            $sqli = "SELECT * FROM lists";
            if ($result = mysqli_query($conn, $sqli)) {
                if (mysqli_num_rows($result) > 0) {
                    echo '<form method="post" action="">';
                    echo '<ul class="list-group">';
                    while ($row = mysqli_fetch_assoc($result)) {
                        $taskClass = $row["completed"] ? "completed list-group-item" : "list-group-item";
                        echo '<li class="' . $taskClass . '">';
                        echo '<input type="checkbox" name="complete" value="' . $row["id"] . '" onchange="this.form.submit()" ' . ($row["completed"] ? "checked" : "") . '> ';
                        echo '<span>' . $row["task"] . '</span>';
                        echo '<button type="submit" name="delete" value="' . $row["id"] . '" class="btn btn-dark btn-sm float-end">Delete</button>';
                        echo '</li>';
                    }
                    echo '</ul>';
                    echo '</form>';
                    mysqli_free_result($result);
                } else {
                    echo "<br>No tasks found.<br>";
                }
            } else {
                echo "<br>Error: Could not retrieve tasks.<br>";
            }

            mysqli_close($conn);
            ?>
        </div>
        </form>
        <footer class="py-3 mt-3">
            <div class="container text-white text-center">
                <small class="text-white-50">&copy;2024</small>
            </div>
        </footer>
    </section>
</body>
</html>
