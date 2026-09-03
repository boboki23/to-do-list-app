<?php

namespace App\Http\Controllers;

// use App\Models\Task;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil semua tasks milik user yang login
        $tasks = Auth::user()->tasks()
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // Hitung statistik
        $allTasks = Auth::user()->tasks;
        $stats = [
            'total' => $allTasks->count(),
            'pending' => $allTasks->where('status', 'pending')->count(),
            'in_progress' => $allTasks->where('status', 'in_progress')->count(),
            'completed' => $allTasks->where('status', 'completed')->count(),
        ];

        return view('dashboard', compact('tasks', 'stats'));
    }
}