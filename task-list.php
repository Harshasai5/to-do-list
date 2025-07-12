<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'db.php';

$user_id = $_SESSION['user_id'];
$tasks = [];

// Sorted query: pending (due date ASC), then no due date, then completed
$stmt = $conn->prepare("
    SELECT * FROM tasks 
    WHERE user_id = ? 
    ORDER BY 
        CASE 
            WHEN status = 'pending' AND due_date IS NOT NULL THEN 0
            WHEN status = 'pending' AND due_date IS NULL THEN 1
            WHEN status = 'completed' THEN 2
        END,
        due_date ASC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }
}

// Group tasks
$pending_due = [];
$pending_nodue = [];
$completed = [];

foreach ($tasks as $task) {
    if ($task['status'] === 'completed') {
        $completed[] = $task;
    } elseif (empty($task['due_date'])) {
        $pending_nodue[] = $task;
    } else {
        $pending_due[] = $task;
    }
}

function renderTaskCard($taskItem) {
    $isDone = $taskItem['status'] === 'completed';
    ?>
    <div class="task-card card mb-3 <?= $isDone ? 'bg-success-subtle text-decoration-line-through' : '' ?>">
        <div class="card-body">
            <h5 class="card-title d-flex align-items-center">
                <input type="checkbox" class="form-check-input me-2 toggle-status"
                    data-id="<?= $taskItem['id'] ?>"
                    <?= $isDone ? 'checked' : '' ?>>
                <?= htmlspecialchars($taskItem['title']) ?>
            </h5>

            <p class="card-text"><?= htmlspecialchars($taskItem['description']) ?></p>

            <?php if (!empty($taskItem['due_date'])): ?>
                <p class="text-muted mb-1"><strong>Due:</strong> <?= date('F j, Y', strtotime($taskItem['due_date'])) ?></p>
            <?php endif; ?>

            <small class="text-muted">Created on: <?= date('F j, Y', strtotime($taskItem['created_at'])) ?></small>

            <div class="task-actions mt-2">
                <button class="btn btn-warning btn-sm edit-btn"
                    data-id="<?= $taskItem['id'] ?>"
                    data-title="<?= htmlspecialchars($taskItem['title'], ENT_QUOTES) ?>"
                    data-description="<?= htmlspecialchars($taskItem['description'], ENT_QUOTES) ?>">
                    Edit
                </button>
                <a href="operations.php?deleteTask=true&id=<?= $taskItem['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
            </div>
        </div>
    </div>
    <?php
}
?>

<?php if (!empty($pending_due)): ?>
    <h5 class="mb-2 text-primary">🟡 Upcoming Tasks</h5>
    <?php foreach ($pending_due as $task) renderTaskCard($task); ?>
<?php endif; ?>

<?php if (!empty($pending_nodue)): ?>
    <h5 class="mt-4 mb-2 text-secondary">📌 No Due Date</h5>
    <?php foreach ($pending_nodue as $task) renderTaskCard($task); ?>
<?php endif; ?>

<?php if (!empty($completed)): ?>
    <h5 class="mt-4 mb-2 text-success">✅ Completed Tasks</h5>
    <?php foreach ($completed as $task) renderTaskCard($task); ?>
<?php endif; ?>
