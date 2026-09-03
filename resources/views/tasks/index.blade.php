@extends('layouts.app')

@section('title', 'Daftar Task')

@section('content')
<div class="dashboard-container">
    
    <!-- ========== HEADER ========== -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="color: #14213d; font-size: 1.8rem; font-weight: 700;">
                <i class="fas fa-list" style="color: #3f6bf0;"></i>
                Daftar Task
            </h1>
            <p style="color: #64748b; font-size: 0.95rem;">Kelola semua task Anda di sini</p>
        </div>
        <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
            <a href="{{ route('dashboard') }}" class="btn-secondary" style="text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-arrow-left"></i> Dashboard
            </a>
            <a href="{{ route('tasks.create') }}" class="btn-primary" style="text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-plus"></i> Tambah Task
            </a>
        </div>
    </div>

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

    <!-- ========== TASK LIST ========== -->
    <div style="background: white; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden;">
        <!-- Header List -->
        <div style="padding: 1rem 1.5rem; background: #f8fafc; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
            <span style="color: #64748b; font-weight: 500;">
                <i class="fas fa-tasks" style="color: #3f6bf0; margin-right: 0.5rem;"></i>
                Total {{ $tasks->total() }} task
            </span>
        </div>

        @if($tasks->isEmpty())
            <!-- Empty State -->
            <div style="padding: 4rem 2rem; text-align: center;">
                <div style="font-size: 4rem; color: #cbd5e1; margin-bottom: 1rem;">
                    <i class="fas fa-inbox"></i>
                </div>
                <h3 style="color: #64748b; font-size: 1.2rem; margin-bottom: 0.5rem;">Belum ada task</h3>
                <p style="color: #94a3b8; margin-bottom: 1.5rem;">Mulai dengan membuat task pertama Anda</p>
                <a href="{{ route('tasks.create') }}" class="btn-primary" style="text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-plus"></i> Tambah Task Sekarang
                </a>
            </div>
        @else
            <!-- Task Items -->
            @foreach($tasks as $task)
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 1.5rem; border-bottom: 1px solid #f1f5f9; transition: all 0.2s; flex-wrap: wrap; gap: 0.5rem; cursor: pointer;" 
                 onclick="window.location='{{ route('tasks.show', $task) }}'"
                 onmouseover="this.style.backgroundColor='#f8fafc'" 
                 onmouseout="this.style.backgroundColor='transparent'">
                
                <!-- Task Info -->
                <div style="display: flex; align-items: center; gap: 1rem; flex: 1; min-width: 200px;" onclick="event.stopPropagation();">
                    <div>
                        <p style="margin: 0; color: #14213d; font-weight: 500; {{ $task->status == 'completed' ? 'text-decoration: line-through; color: #94a3b8;' : '' }}">
                            {{ $task->title }}
                        </p>
                        @if($task->description)
                            <p style="margin: 0; color: #64748b; font-size: 0.85rem;">{{ Str::limit($task->description, 80) }}</p>
                        @endif
                    </div>
                </div>

                <!-- Task Meta -->
                <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;" onclick="event.stopPropagation();">
                    <!-- Priority Badge -->
                    <span style="font-size: 0.75rem; padding: 0.2rem 0.6rem; border-radius: 20px; font-weight: 500;
                        @if($task->priority == 'high') background: #fee2e2; color: #dc2626;
                        @elseif($task->priority == 'medium') background: #fef3c7; color: #d97706;
                        @else background: #d1fae5; color: #065f46; @endif">
                        {{ ucfirst($task->priority) }}
                    </span>
                    
                    <!-- Status Dropdown -->
                    <form method="POST" action="{{ route('tasks.update-status', $task) }}" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <select name="status" onchange="this.form.submit()" 
                                style="padding: 0.25rem 0.6rem; border-radius: 12px; border: 1.5px solid #e2e8f0; font-size: 0.75rem; cursor: pointer; background: white; font-weight: 500; transition: all 0.2s;"
                                onmouseover="this.style.borderColor='#3f6bf0'"
                                onmouseout="this.style.borderColor='#e2e8f0'">
                            <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }} style="color: #d97706;">⏳ Pending</option>
                            <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }} style="color: #3b82f6;">🔄 In Progress</option>
                            <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }} style="color: #22c55e;">✅ Completed</option>
                        </select>
                    </form>
                    
                    <!-- Due Date -->
                    @if($task->due_date)
                        <span style="font-size: 0.75rem; color: #64748b;">
                            <i class="fas fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($task->due_date)->translatedFormat('d M Y') }}
                        </span>
                    @endif

                    <!-- Action Buttons -->
                    <div style="display: flex; gap: 0.25rem;">
                        <a href="{{ route('tasks.edit', $task) }}" 
                           style="background: none; border: none; color: #3b82f6; cursor: pointer; padding: 0.25rem 0.5rem; text-decoration: none; transition: all 0.2s;"
                           onmouseover="this.style.color='#1e40af'; this.style.transform='scale(1.1)'"
                           onmouseout="this.style.color='#3b82f6'; this.style.transform='scale(1)'"
                           title="Edit Task">
                            <i class="fas fa-edit"></i>
                        </a>
                        
                        <form method="POST" action="{{ route('tasks.destroy', $task) }}" 
                              style="display: inline;" 
                              class="delete-form"
                              data-title="{{ $task->title }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="delete-btn" 
                                    style="background: none; border: none; color: #dc2626; cursor: pointer; padding: 0.25rem 0.5rem; transition: all 0.2s;"
                                    onmouseover="this.style.color='#b91c1c'; this.style.transform='scale(1.1)'"
                                    onmouseout="this.style.color='#dc2626'; this.style.transform='scale(1)'"
                                    data-id="{{ $task->id }}"
                                    data-title="{{ $task->title }}"
                                    title="Hapus Task">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
            
            <!-- Pagination -->
            <div style="padding: 1rem 1.5rem; background: #f8fafc; border-top: 1px solid #e2e8f0;">
                {{ $tasks->links() }}
            </div>
        @endif
    </div>
</div>

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
@endsection