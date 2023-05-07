<!-- index.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Simple To-Do List</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Simple To-Do List</h1>

        <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <input type="text" name="task" placeholder="Enter a task..." required>
            <button type="submit">Add Task</button>
        </form>

        <ul class="task-list">
            <?php
            // Check if the form is submitted
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $task = $_POST["task"];

                // Append the new task to the tasks.txt file
                file_put_contents("tasks.txt", $task . PHP_EOL, FILE_APPEND);
            }

            // Read the tasks from the tasks.txt file
            $tasks = file("tasks.txt", FILE_IGNORE_NEW_LINES);

            foreach ($tasks as $task) {
                echo "<li>";
                echo "<span class='task'>" . htmlspecialchars($task) . "</span>";
                echo "<span class='actions'>";
                echo "<a class='button button-complete' href='?action=complete&task=" . urlencode($task) . "'>Complete</a>";
                echo "<a class='button button-delete' href='?action=delete&task=" . urlencode($task) . "'>Delete</a>";
                echo "</span>";
                echo "</li>";
            }

            // Handle task completion and deletion
            if (isset($_GET["action"]) && isset($_GET["task"])) {
                $action = $_GET["action"];
                $task = $_GET["task"];

                if ($action === "complete") {
                    // Remove the completed task from the tasks.txt file
                    $tasks = array_diff($tasks, [$task]);
                    file_put_contents("tasks.txt", implode(PHP_EOL, $tasks));
                } elseif ($action === "delete") {
                    // Remove the task from the tasks.txt file
                    $tasks = array_diff($tasks, [$task]);
                    file_put_contents("tasks.txt", implode(PHP_EOL, $tasks));
                }

                // Redirect back to the index.php to prevent resubmission
                header("Location: index.php");
                exit();
            }
            ?>
        </ul>
    </div>
</body>
</html>