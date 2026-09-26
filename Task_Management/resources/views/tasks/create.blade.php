<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Task - KuTasks</title>
    
    <!-- Google Fonts for modern tech typography -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/create.css">
</head>

<body>

<div class="container">

    <div class="header">
        <h1>Add New Task</h1>
        <p>Create a new task and keep track of your work.</p>
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

        <form action="/tasks" method="POST">
            @csrf

            <div class="form-group">
                <label for="task_name">Task Name</label>
                <input 
                    type="text" 
                    id="task_name" 
                    name="task_name" 
                    value="{{ old('task_name') }}" 
                    placeholder="Enter task name" 
                    required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea 
                    id="description" 
                    name="description" 
                    rows="5" 
                    placeholder="Enter task description">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="Pending" {{ old('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Completed" {{ old('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            <div class="form-group">
                <label for="due_date">Due Date</label>
                <input 
                    type="date" 
                    id="due_date" 
                    name="due_date" 
                    value="{{ old('due_date') }}">
            </div>

            <div class="buttons">
                <button type="submit" class="add-button">
                    Add Task
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