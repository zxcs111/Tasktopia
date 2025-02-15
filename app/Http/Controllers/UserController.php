<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Method to display all users for management
    public function index()
    {
        // Ensure only an admin can access this method
        if (Auth::user()->user_role !== 1) {
            return redirect()->back()->withErrors(['error' => 'Unauthorized action.']);
        }

        // Get paginated users (5 per page, adjust as needed)
        $users = User::paginate(9);
        return view('manage-users', compact('users'));
    }

    // Method to update user details
    public function update(Request $request, $id)
    {
        // Ensure only an admin can update user details
        if (Auth::user()->user_role !== 1) {
            return redirect()->back()->withErrors(['error' => 'Unauthorized action.']);
        }

        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'user_role' => 'required|in:0,1', // Ensure role is either 0 or 1
        ]);

        // Find the user and update their details
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->user_role = $request->user_role;
        $user->save();

        return redirect()->route('manage.users')->with('success', 'User updated successfully!');
    }

    // Method to delete a user
    public function destroy($id)
    {
        // Ensure only an admin can delete users
        if (Auth::user()->user_role !== 1) {
            return redirect()->back()->withErrors(['error' => 'Unauthorized action.']);
        }

        // Find the user and delete
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('manage.users')->with('success', 'User deleted successfully!');
    }

    // Method to update user role (if needed separately)
    public function updateUserRole(Request $request, $id)
    {
        // Ensure only an admin can update user roles
        if (Auth::user()->user_role !== 1) {
            return redirect()->back()->withErrors(['error' => 'Unauthorized action.']);
        }

        $request->validate([
            'user_role' => 'required|in:0,1', // Ensure role is either 0 or 1
        ]);

        $user = User::findOrFail($id);
        $user->user_role = $request->user_role;
        $user->save();

        return redirect()->route('manage.users')->with('success', 'User role updated successfully!');
    }

    // Method to add a new user
    public function store(Request $request)
    {
        // Ensure only an admin can add users
        if (Auth::user()->user_role !== 1) {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }
    
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email', // Ensure email is unique
            'password' => 'required|string|min:8', // Ensure password is provided and meets criteria
            'user_role' => 'required|in:0,1', // Ensure role is either 0 or 1
        ]);
    
        // Check if email already exists
        $existingUser = User::where('email', $request->email)->first();
        if ($existingUser) {
            // If email exists, return a JSON response with error
            return response()->json(['error' => 'Email is already taken!'], 400);
        }
    
        // Create the user if the email is unique
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hash the password
            'user_role' => $request->user_role,
        ]);
    
        return response()->json(['success' => 'User added successfully!']);
    }

    // UserController.php

    public function search(Request $request)
    {
        // Ensure only admin can search
        if (Auth::user()->user_role !== 1) {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }

        $email = $request->input('email');
        
        // Filter users by email, specifically for Gmail addresses
        $users = User::where('email', 'like', '%' . $email . '%')
                    ->where('email', 'like', '%@gmail.com')
                    ->get();
        
        return response()->json(['users' => $users]);
    }

    
}