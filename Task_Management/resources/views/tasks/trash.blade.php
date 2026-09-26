<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trash Bin - KuTasks</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/index.css"> <!-- Reuses main dashboard CSS -->
    <link rel="stylesheet" href="/css/delete.css">  <!-- Reuses modal CSS -->
</head>

<body>
<div class="container">

    <div class="dashboard-header">
        <div class="header">
            <h1>Trash <span class="highlight">Bin</span></h1>
            <p>Manage deleted tasks. Restore them or delete them permanently.</p>
        </div>

        <a href="/tasks" class="add-button" style="background: rgba(255,255,255,0.05); border: 1px solid var(--border-color);">
            ← Back to Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    <div class="tasks-grid">
        @forelse($tasks as $task)
            <div class="task-card">
                <div>
                    <h2>{{ $task->task_name }}</h2>
                    <p class="description">
                        {{ $task->description ?: 'No description provided.' }}
                    </p>
                </div>

                <div>
                    <div class="task-info">
                        <div>
                            <strong>Deleted At:</strong> {{ $task->deleted_at->format('M d, Y H:i') }}
                        </div>
                    </div>

                    <div class="actions">
                        <!-- Restore Form -->
                        <form action="/tasks/{{ $task->id }}/restore" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="status-button">
                                ↺ Restore
                            </button>
                        </form>

                        <!-- Permanent Delete Form (Modified to use Modal) -->
                        <form action="/tasks/{{ $task->id }}/force-delete" method="POST" onsubmit="return false;">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="delete-button" onclick="openDeleteModal(this)">
                                Delete Permanently
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty">
                <h2>Trash is Empty</h2>
                <p>No deleted tasks found.</p>
            </div>
        @endforelse
    </div>

</div>

<!-- Permanent Delete Confirmation Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-backdrop" onclick="closeDeleteModal()"></div>
    
    <div class="modal-content">
        <div class="warning-icon">
            <span>!</span>
        </div>

        <h2>Delete Permanently?</h2>
        
        <p>
            This action cannot be undone. Are you sure you want to permanently delete this task?
        </p>

        <div class="modal-buttons">
            <button
                type="button"
                class="cancel-modal"
                onclick="closeDeleteModal()">
                Cancel
            </button>

            <button
                type="button"
                class="confirm-delete"
                onclick="confirmDelete()">
                Yes, Delete
            </button>
        </div>
    </div>
</div>

<script>
    let deleteForm = null;

    function openDeleteModal(button) {
        deleteForm = button.closest('form');
        document.getElementById('deleteModal').style.display = 'flex';
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
        deleteForm = null;
    }

    function confirmDelete() {
        if (deleteForm) {
            deleteForm.onsubmit = null;
            deleteForm.submit();
        }
    }
</script>
</body>
</html>