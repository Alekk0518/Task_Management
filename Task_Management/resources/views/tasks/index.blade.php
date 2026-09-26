<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Task Manager - Dashboard</title>
    
    <!-- Google Fonts for modern tech typography -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="/css/task.css">
    <link rel="stylesheet" href="/css/index.css">

       
</head>

<body>

<div class="container">

    <div class="dashboard-header">
        <div class="header">
                <h1>Ku<span class="highlight">Tasks</span></h1>
                <p>Organize your tasks and keep track of your progress effortlessly.</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="/tasks/trash" class="trash-bin-btn">
                <span class="trash-icon">🗑️</span> Trash Bin
            </a>
            <a href="/tasks/create" class="add-button">
                + Add New Task
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    <div class="stats">
        <div class="stat-card">
            <h3>Total Tasks</h3>
            <div class="number">{{$totalCount }}</div>
        </div>

        <div class="stat-card">
            <h3>Pending</h3>
            <div class="number1">{{$pendingCount}}</div>
        </div>

        <div class="stat-card">
            <h3>Completed</h3>
            <div class="number2">{{ $completedCount }}</div>
        </div>
    </div>
    <!-- Keyword Search Bar -->
  <!-- Keyword Search Bar -->
    <div style="margin-bottom: 16px;">
        <div class="search-form">
            @if(request('filter'))
                <input type="hidden" id="current-filter" value="{{ request('filter') }}">
            @endif

            <div class="search-input-group">
                <span class="search-icon">🔍</span>
                <input 
                    type="text" 
                    id="search-input"
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Search tasks by title or description..." 
                    autocomplete="off"
                    class="search-input">
                
                <a href="{{ route('tasks.index', ['filter' => request('filter')]) }}" id="clear-search-btn" class="clear-search" style="{{ request('search') ? '' : 'display: none;' }}" title="Clear search">✕</a>
            </div>
        </div>
    </div>
    <!-- Date Filter Bar -->
    <div class="filter-bar">
        <a href="/tasks?filter=all" class="filter-btn {{ ($filter ?? 'all') === 'all' ? 'active' : '' }}">
            📋 All Tasks
        </a>
        <a href="/tasks?filter=today" class="filter-btn {{ ($filter ?? '') === 'today' ? 'active' : '' }}">
            📅 Due Today
        </a>
        <a href="/tasks?filter=upcoming" class="filter-btn {{ ($filter ?? '') === 'upcoming' ? 'active' : '' }}">
            ⏳ Upcoming
        </a>
        <a href="/tasks?filter=overdue" class="filter-btn {{ ($filter ?? '') === 'overdue' ? 'active' : '' }}">
            ⚠️ Overdue
        </a>
    </div>
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
                            <strong>Due:</strong> {{ $task->due_date ?? 'No due date' }}
                        </div>
                        <span class="status {{ $task->status === 'Pending' ? 'pending' : 'completed' }}">
                            {{ $task->status }}
                        </span>
                    </div>

                    <div class="actions">
                        <a href="/tasks/{{ $task->id }}/edit" class="edit-button">
                            Edit
                        </a>

                        <form action="/tasks/{{ $task->id }}/status" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="status-button">
                                {{ $task->status === 'Pending' ? '✓ Complete' : '↩ Pending' }}
                            </button>
                        </form>

                        <form action="/tasks/{{ $task->id }}" method="POST" onsubmit="return false;">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="delete-button" onclick="openDeleteModal(this)">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty">
                <h2>No Tasks Found</h2>
                <p>You don't have any tasks yet. Get started by adding your first task!</p>
            </div>
        @endforelse
    </div>

    @include('tasks.delete-modal')
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
<script>
    let searchTimeout = null;

    document.getElementById('search-input').addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        let query = e.target.value;
        let filter = document.getElementById('current-filter') ? document.getElementById('current-filter').value : 'all';
        
        let clearBtn = document.getElementById('clear-search-btn');
        clearBtn.style.display = query.length > 0 ? 'block' : 'none';

        // Wait 300ms after user stops typing to avoid too many requests
        searchTimeout = setTimeout(() => {
            let url = `/tasks?search=${encodeURIComponent(query)}&filter=${filter}`;

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                // Parse the returned HTML page
                let parser = new DOMParser();
                let doc = parser.parseFromString(html, 'text/html');

                // Extract and replace just the tasks grid and stats container
                let newGrid = doc.querySelector('.tasks-grid');
                let newStats = doc.querySelector('.stats-container');

                if (newGrid) {
                    document.querySelector('.tasks-grid').innerHTML = newGrid.innerHTML;
                }
                if (newStats) {
                    document.querySelector('.stats-container').innerHTML = newStats.innerHTML;
                }
            });
        }, 300);
    });
</script>
</body>
</html>