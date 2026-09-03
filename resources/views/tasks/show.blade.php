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
            <p style="color: #64748b;">Semua task Anda dalam satu tempat</p>
        </div>
        <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
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
        <div style="padding: 1rem 1.5rem; background: #f8fafc; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
            <span style="color: #64748b;">Total {{ $tasks->total() }} task</span>
            <a href="{{ route('dashboard') }}" style="color: #3f6bf0; text-decoration: none; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 0.25rem;">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        @if($tasks->isEmpty())
            <div style="padding: 3rem 2rem; text-align: center;">
                <div style="font-size: 3rem; color: #cbd5e1; margin-bottom: 1rem;">
                    <i class="fas fa-inbox"></i>
                </div>
                <h3 style="color: #64748b;">Belum ada task</h3>
                <p style="color: #94a3b8;">Klik "Tambah Task" untuk membuat task pertama</p>
                <div style="margin-top: 1.5rem; display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap;">
                    <a href="{{ route('tasks.create') }}" class="btn-primary" style="text-decoration: none;">
                        <i class="fas fa-plus"></i> Tambah Task
                    </a>
                </div>
            </div>
        @else
            @foreach($tasks as $task)
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 1.5rem; border-bottom: 1px solid #f1f5f9; transition: all 0.2s; flex-wrap: wrap; gap: 0.5rem; cursor: pointer;" 
                 onclick="window.location='{{ route('tasks.show', $task) }}'">
                
                <div style="display: flex; align-items: center; gap: 1rem; flex: 1; min-width: 200px;">
                    <div onclick="event.stopPropagation();">
                        <p style="margin: 0; color: #14213d; font-weight: 500; {{ $task->status == 'completed' ? 'text-decoration: line-through; color: #94a3b8;' : '' }}">
                            {{ $task->title }}
                        </p>
                        @if($task->description)
                            <p style="margin: 0; color: #64748b; font-size: 0.85rem;">{{ Str::limit($task->description, 80) }}</p>
                        @endif
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;" onclick="event.stopPropagation();">
                    <span style="font-size: 0.75rem; padding: 0.2rem 0.6rem; border-radius: 20px; 
                        @if($task->priority == 'high') background: #fee2e2; color: #dc2626;
                        @elseif($task->priority == 'medium') background: #fef3c7; color: #d97706;
                        @else background: #d1fae5; color: #065f46; @endif">
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
                        <span style="font-size: 0.75rem; color: #64748b;">
                            <i class="fas fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($task->due_date)->translatedFormat('d M Y') }}
                        </span>
                    @endif

                    <div style="display: flex; gap: 0.25rem;">
                        <a href="{{ route('tasks.edit', $task) }}" style="background: none; border: none; color: #3b82f6; cursor: pointer; padding: 0.25rem 0.5rem; text-decoration: none;" onclick="event.stopPropagation();">
                            <i class="fas fa-edit"></i>
                        </a>
                        
                        <form method="POST" action="{{ route('tasks.destroy', $task) }}" 
                              style="display: inline;" 
                              class="delete-form"
                              data-title="{{ $task->title }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="delete-btn" 
                                    style="background: none; border: none; color: #dc2626; cursor: pointer; padding: 0.25rem 0.5rem;"
                                    data-id="{{ $task->id }}"
                                    data-title="{{ $task->title }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
            
            <!-- Pagination -->
            <div style="padding: 1rem 1.5rem; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                <div>
                    {{ $tasks->links() }}
                </div>
                <a href="{{ route('dashboard') }}" style="color: #3f6bf0; text-decoration: none; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 0.25rem;">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
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