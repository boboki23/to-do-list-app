@extends('layouts.app')

@section('title', 'Dashboard - To-Do List')

@section('content')
<div class="dashboard-container">
    
    <!-- ========== HEADER ========== -->
    <header class="dashboard-header">
        <div class="header-left">
            <h1>
                <i class="fas fa-tasks"></i>
                To-Do List
            </h1>
            <p>
                <i class="fas fa-user"></i> {{ Auth::user()->name }} &nbsp;|&nbsp;
                <i class="fas fa-calendar-alt"></i> 
                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </p>
        </div>
        <div class="header-right">
            <a href="{{ route('tasks.index') }}" class="btn-secondary" style="text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-list"></i> Lihat Semua
            </a>
            <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </header>

    <!-- ========== FLASH MESSAGES ========== -->
    @if (session('success'))
        <div class="flash-message flash-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="flash-message flash-error">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- ========== STATISTICS CARDS ========== -->
    <div class="stats-grid">
        <div class="stat-card stat-total">
            <div class="stat-icon">📋</div>
            <p class="stat-label">Total Tasks</p>
            <p class="stat-number">{{ $stats['total'] ?? 0 }}</p>
        </div>
        <div class="stat-card stat-pending">
            <div class="stat-icon">⏳</div>
            <p class="stat-label">Pending</p>
            <p class="stat-number">{{ $stats['pending'] ?? 0 }}</p>
        </div>
        <div class="stat-card stat-progress">
            <div class="stat-icon">🔄</div>
            <p class="stat-label">In Progress</p>
            <p class="stat-number">{{ $stats['in_progress'] ?? 0 }}</p>
        </div>
        <div class="stat-card stat-completed">
            <div class="stat-icon">✅</div>
            <p class="stat-label">Completed</p>
            <p class="stat-number">{{ $stats['completed'] ?? 0 }}</p>
        </div>
    </div>

    <!-- ========== ADD TASK FORM ========== -->
    <section class="add-task-section">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
            <h3>
                <i class="fas fa-plus-circle"></i>
                Tambah Task Baru
            </h3>
            <a href="{{ route('tasks.create') }}" class="btn-secondary" style="text-decoration: none;">
                <i class="fas fa-plus"></i> Form Lengkap
            </a>
        </div>
        <form class="task-form" method="POST" action="{{ route('tasks.store') }}">
            @csrf
            <input type="text" name="title" class="form-input" placeholder="Judul task..." required>
            <input type="text" name="description" class="form-input" placeholder="Deskripsi task..." style="flex: 2; min-width: 200px;">
            <select name="priority" class="form-select">
                <option value="low">🟢 Low</option>
                <option value="medium" selected>🟡 Medium</option>
                <option value="high">🔴 High</option>
            </select>
            <input type="date" name="due_date" class="form-date">
            <button type="submit" class="btn-primary">
                <i class="fas fa-plus"></i> Tambah
            </button>
        </form>
        
        @if ($errors->any())
            <div style="margin-top: 1rem; padding: 0.75rem 1rem; background: #fee2e2; border: 1px solid #fecaca; border-radius: 12px; color: #dc2626;">
                <ul style="list-style: none; padding: 0; margin: 0;">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </section>

    <!-- ========== TASK LIST ========== -->
    <section class="task-list-section">
        <div class="task-list-header">
            <h3>
                <i class="fas fa-list"></i>
                Daftar Task
            </h3>
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <span class="task-count">{{ isset($tasks) ? $tasks->total() : 0 }} task</span>
                <a href="{{ route('tasks.index') }}" style="color: #3f6bf0; text-decoration: none; font-size: 0.85rem; font-weight: 500;">
                    Lihat Semua <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        @if(!isset($tasks) || $tasks->isEmpty())
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-inbox"></i>
                </div>
                <h3>Belum ada task</h3>
                <p>Tambahkan task pertama Anda di atas</p>
            </div>
        @else
            @foreach($tasks as $task)
            <div class="task-item" id="task-{{ $task->id }}" 
                 onclick="window.location='{{ route('tasks.show', $task) }}'"
                 style="cursor: pointer;">
                
                <div class="task-info">
                    <div onclick="event.stopPropagation();">
                        <p class="task-title {{ $task->status == 'completed' ? 'completed' : '' }}">
                            {{ $task->title }}
                        </p>
                        @if($task->description)
                            <p class="task-description">{{ Str::limit($task->description, 100) }}</p>
                        @endif
                    </div>
                </div>
                
                <div class="task-meta" onclick="event.stopPropagation();">
                    <span class="badge 
                        @if($task->priority == 'high') badge-high
                        @elseif($task->priority == 'medium') badge-medium
                        @else badge-low @endif">
                        {{ ucfirst($task->priority) }}
                    </span>
                    
                    <form method="POST" action="{{ route('tasks.update-status', $task) }}" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <select name="status" onchange="this.form.submit()" 
                                style="padding: 0.25rem 0.6rem; border-radius: 12px; border: 1.5px solid #e2e8f0; font-size: 0.75rem; cursor: pointer; background: white; font-weight: 500;">
                            <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }} style="color: #d97706;">⏳ Pending</option>
                            <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }} style="color: #3b82f6;">🔄 In Progress</option>
                            <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }} style="color: #22c55e;">✅ Completed</option>
                        </select>
                    </form>
                    
                    @if($task->due_date)
                        <span class="task-due-date">
                            <i class="fas fa-calendar-alt"></i> 
                            {{ \Carbon\Carbon::parse($task->due_date)->translatedFormat('d M Y') }}
                        </span>
                    @endif

                    <div style="display: flex; gap: 0.25rem;">
                        <a href="{{ route('tasks.edit', $task) }}" class="btn-edit" title="Edit" onclick="event.stopPropagation();">
                            <i class="fas fa-edit"></i>
                        </a>
                        
                        <form method="POST" action="{{ route('tasks.destroy', $task) }}" 
                              style="display: inline;" 
                              class="delete-form"
                              data-title="{{ $task->title }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn-delete delete-btn" title="Hapus" 
                                    data-id="{{ $task->id }}"
                                    data-title="{{ $task->title }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
            
            @if(method_exists($tasks, 'links'))
                <div style="padding: 1rem 1.5rem; background: #f8fafc; border-top: 1px solid #e2e8f0;">
                    {{ $tasks->links() }}
                </div>
            @endif
        @endif
    </section>

    <footer class="dashboard-footer">
        &copy; {{ date('Y') }} To-Do List App. All rights reserved.
        <span style="margin: 0 0.5rem;">•</span>
        <i class="fas fa-heart" style="color: #ef4444;"></i> Made with Laravel
    </footer>
</div>
@endsection

@push('scripts')
<script>
    // SweetAlert untuk konfirmasi delete
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const taskTitle = this.dataset.title;
            const form = this.closest('.delete-form');
            
            Swal.fire({
                title: 'Apakah Anda yakin?',
                html: `Task "<strong>${taskTitle}</strong>" akan dihapus secara permanen!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush