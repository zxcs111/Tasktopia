<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;

class DashboardController extends Controller
{
    public function index()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Get tasks for the authenticated user that are not completed
        $tasks = Task::where('user_id', $user->id)
                     ->where('status', '!=', 'Completed') // Exclude completed tasks
                     ->get();

        // Return the dashboard view with user and task data
        return view('dashboard', compact('user', 'tasks'));
    }
}