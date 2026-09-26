<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task - Kutasks</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/edit.css">
</head>

<body>

<div class="container">

    <div class="header">
        <h1>Edit Task</h1>
        <p>Update your task details below.</p>
    </div>

    <div class="form-card">

        @if($errors->any())
            <div class="error-box">
                <strong>Please fix the following errors:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Notice the action points to the specific task ID and uses PUT method -->
        <form action="/tasks/{{ $task->id }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="task_name">Task Name</label>
                <input 
                    type="text" 
                    id="task_name" 
                    name="task_name" 
                    value="{{ old('task_name', $task->task_name) }}" 
                    placeholder="Enter task name" 
                    required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea 
                    id="description" 
                    name="description" 
                    rows="5" 
                    placeholder="Enter task description">{{ old('description', $task->description) }}</textarea>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="Pending" {{ old('status', $task->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Completed" {{ old('status', $task->status) == 'Completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            <div class="form-group">
                <label for="due_date">Due Date</label>
                <input 
                    type="date" 
                    id="due_date" 
                    name="due_date" 
                    value="{{ old('due_date', $task->due_date) }}">
            </div>

            <div class="buttons">
                <button type="submit" class="add-button">
                    Update Task
                </button>

                <a href="/tasks" class="cancel-button">
                    Cancel
                </a>
            </div>

        </form>

    </div>

</div>

</body>
</html>