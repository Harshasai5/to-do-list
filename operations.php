<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    // Redirect to login or show error
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

# 🔹 Add Task
if (isset($_POST['add_task'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date']; // New field

    $stmt = $conn->prepare("INSERT INTO tasks (title, description, due_date, user_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $title, $description, $due_date, $user_id);

    if ($stmt->execute()) {
        header("Location: index.php?success=Task added successfully");
    } else {
        header("Location: index.php?error=Failed to add task");
    }
    exit;
}

# 🔹 Edit Task (title & description only)
if (isset($_POST['edit_task'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("UPDATE tasks SET title = ?, description = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ssii", $title, $description, $id, $user_id);

    if ($stmt->execute()) {
        header("Location: index.php?success=Task updated successfully");
    } else {
        header("Location: index.php?error=Failed to update task");
    }
    exit;
}

# 🔹 Delete Task
if (isset($_GET['deleteTask']) && isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $user_id);

    if ($stmt->execute()) {
        header("Location: index.php?success=Task deleted successfully");
    } else {
        header("Location: index.php?error=Failed to delete task");
    }
    exit;
}

# 🔹 Update Task Status (via checkbox toggle)
if (isset($_GET['updateStatus']) && isset($_GET['id']) && isset($_GET['status'])) {
    $id = $_GET['id'];
    $status = $_GET['status'];

    $stmt = $conn->prepare("UPDATE tasks SET status = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("sii", $status, $id, $user_id);
    $stmt->execute();
}
?>
