<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'db.php';
require_once 'operations.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>To-Do List</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="style.css" />

  <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
<script>
  window.OneSignalDeferred = window.OneSignalDeferred || [];
  OneSignalDeferred.push(async function(OneSignal) {
    await OneSignal.init({
      appId: "b9410263-1a9c-4b07-98bc-edec4533dbfc",
    });
  });
</script>

</head>
<body>

<!-- Navbar with Centered Title, Left User Info, Right Logout -->
<nav class="navbar navbar-dark" style="background-color: #1d2630;">
  <div class="container-fluid">
    <div class="row w-100 text-center align-items-center">

      <!-- Left: Hello -->
      <div class="col-4 text-start ps-3">
        <span class="navbar-text text-white">
          👋 Hello, <?php echo $_SESSION['username']; ?>
        </span>
      </div>

      <!-- Center: Title -->
      <div class="col-4 text-center">
        <span class="navbar-brand mx-auto fw-bold" style="font-size: 1.5rem;">📝 To-Do App</span>
      </div>

      <!-- Right: Logout -->
      <div class="col-4 text-end pe-3">
        <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
      </div>

    </div>
  </div>
</nav>

<div class="container my-4">
  <div class="row justify-content-center">
    <!-- Add Task Form -->
    <div class="col-lg-6">
      <div class="card mb-4">
        <div class="card-header bg-dark text-white">Add New Task</div>
        <div class="card-body">
          <form action="operations.php" method="post">
            <div class="mb-3">
              <label for="taskTitle" class="form-label">Task Title</label>
              <input type="text" class="form-control" id="taskTitle" name="title" required>
            </div>
            <div class="mb-3">
              <label for="taskDescription" class="form-label">Task Description</label>
              <textarea name="description" id="taskDescription" class="form-control" rows="3" required></textarea>
            </div>
            <div class="mb-3">
              <label for="dueDate" class="form-label">Due Date</label>
              <input type="date" class="form-control" id="dueDate" name="due_date">
            </div>
            <button class="btn btn-success w-100" type="submit" name="add_task">Add Task</button>
          </form>
        </div>
      </div>
    </div>

    <!-- Task List -->
    <div class="col-lg-6">
      <div class="card">
        <div class="card-header bg-dark text-white">Your Tasks</div>
        <div class="card-body">
          <?php include 'task-list.php'; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'edit-modal.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", () => {
    // ✅ Edit Modal trigger
    document.querySelectorAll('.edit-btn').forEach(button => {
      button.addEventListener('click', () => {
        const id = button.getAttribute('data-id');
        const title = button.getAttribute('data-title');
        const description = button.getAttribute('data-description');

        document.getElementById('editTaskId').value = id;
        document.getElementById('editTaskTitle').value = title;
        document.getElementById('editTaskDescription').value = description;

        const editModal = new bootstrap.Modal(document.getElementById('editTaskModal'));
        editModal.show();
      });
    });

    // ✅ Completion checkbox toggle
    document.querySelectorAll('.toggle-status').forEach(checkbox => {
      checkbox.addEventListener('change', () => {
        const taskId = checkbox.getAttribute('data-id');
        const status = checkbox.checked ? 'completed' : 'pending';

        fetch(`operations.php?updateStatus=1&id=${taskId}&status=${status}`)
          .then(() => location.reload());
      });
    });
  });
</script>
</body>
</html>
