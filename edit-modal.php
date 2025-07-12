<div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="editTaskModalLabel">Edit Task</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="operations.php" method="post">
        <div class="modal-body">
          <input type="hidden" name="id" id="editTaskId">
          <div class="mb-3">
            <label for="editTaskTitle" class="form-label">Task Title</label>
            <input type="text" class="form-control" id="editTaskTitle" name="title" required>
          </div>
          <div class="mb-3">
            <label for="editTaskDescription" class="form-label">Task Description</label>
            <textarea name="description" id="editTaskDescription" class="form-control" rows="4" required></textarea>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" name="edit_task" class="btn btn-primary">Save Changes</button>
        </div>
      </form>

    </div>
  </div>
</div>
