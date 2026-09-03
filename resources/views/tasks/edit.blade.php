@extends('layouts.app')

@section('title', 'Edit Task')

@section('content')
<div class="dashboard-container">
    <div style="max-width: 700px; margin: 0 auto;">
        
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h1 style="color: #14213d; font-size: 1.8rem; font-weight: 700;">
                <i class="fas fa-edit" style="color: #3f6bf0;"></i>
                Edit Task
            </h1>
            <a href="{{ route('dashboard') }}" class="btn-secondary" style="text-decoration: none;">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <!-- Form -->
        <div style="background: white; border-radius: 16px; border: 1px solid #e2e8f0; padding: 2rem;">
            <form method="POST" action="{{ route('tasks.update', $task) }}">
                @csrf
                @method('PUT')

                <!-- Title -->
                <div style="margin-bottom: 1.5rem;">
                    <label for="title" style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #14213d;">
                        Judul Task <span style="color: #dc2626;">*</span>
                    </label>
                    <input type="text" id="title" name="title" 
                           value="{{ old('title', $task->title) }}" required
                           placeholder="Masukkan judul task..."
                           style="width: 100%; padding: 0.75rem 1rem; border: 1.5px solid #e2e8f0; border-radius: 12px; font-size: 1rem;">
                    @error('title')
                        <span style="color: #dc2626; font-size: 0.85rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Description -->
                <div style="margin-bottom: 1.5rem;">
                    <label for="description" style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #14213d;">
                        Deskripsi
                    </label>
                    <textarea id="description" name="description" 
                              placeholder="Masukkan deskripsi task..."
                              rows="4"
                              style="width: 100%; padding: 0.75rem 1rem; border: 1.5px solid #e2e8f0; border-radius: 12px; font-size: 1rem; font-family: inherit; resize: vertical;">{{ old('description', $task->description) }}</textarea>
                    @error('description')
                        <span style="color: #dc2626; font-size: 0.85rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Status, Priority & Due Date -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div>
                        <label for="status" style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #14213d;">
                            Status <span style="color: #dc2626;">*</span>
                        </label>
                        <select id="status" name="status" 
                                style="width: 100%; padding: 0.75rem 1rem; border: 1.5px solid #e2e8f0; border-radius: 12px; font-size: 1rem; background: white;">
                            <option value="pending" {{ old('status', $task->status) == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                            <option value="in_progress" {{ old('status', $task->status) == 'in_progress' ? 'selected' : '' }}>🔄 In Progress</option>
                            <option value="completed" {{ old('status', $task->status) == 'completed' ? 'selected' : '' }}>✅ Completed</option>
                        </select>
                        @error('status')
                            <span style="color: #dc2626; font-size: 0.85rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="priority" style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #14213d;">
                            Prioritas <span style="color: #dc2626;">*</span>
                        </label>
                        <select id="priority" name="priority" 
                                style="width: 100%; padding: 0.75rem 1rem; border: 1.5px solid #e2e8f0; border-radius: 12px; font-size: 1rem; background: white;">
                            <option value="low" {{ old('priority', $task->priority) == 'low' ? 'selected' : '' }}>🟢 Low</option>
                            <option value="medium" {{ old('priority', $task->priority) == 'medium' ? 'selected' : '' }}>🟡 Medium</option>
                            <option value="high" {{ old('priority', $task->priority) == 'high' ? 'selected' : '' }}>🔴 High</option>
                        </select>
                        @error('priority')
                            <span style="color: #dc2626; font-size: 0.85rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Due Date -->
                <div style="margin-bottom: 1.5rem;">
                    <label for="due_date" style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #14213d;">
                        Tenggat Waktu
                    </label>
                    <input type="date" id="due_date" name="due_date" 
                           value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d') : '') }}"
                           style="width: 100%; padding: 0.75rem 1rem; border: 1.5px solid #e2e8f0; border-radius: 12px; font-size: 1rem;">
                    @error('due_date')
                        <span style="color: #dc2626; font-size: 0.85rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    <button type="submit" class="btn-primary" style="flex: 1; padding: 0.75rem; justify-content: center; font-size: 1rem; min-width: 150px;">
                        <i class="fas fa-save"></i> Update
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn-secondary" 
                       style="flex: 0 0 auto; padding: 0.75rem 2rem; background: #e2e8f0; color: #64748b; border-radius: 12px; text-decoration: none; display: flex; align-items: center; gap: 0.5rem; font-weight: 500;">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection