<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    // Display the profile edit form
    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        Log::info('Update request received:', $request->all());

        // Validate the incoming request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Get the authenticated user
        $user = Auth::user();

        // Check if the user is authenticated
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.'], 404);
        }

        // Update the user's name
        $user->name = $validatedData['name'];

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::delete($user->profile_picture); // Delete the old file
            }
            $path = $request->file('profile_picture')->store('images/profiles', 'public');
            $user->profile_picture = $path; // Save the new path
        }

        // Attempt to save the user
        return $user->save()
            ? response()->json(['success' => true, 'message' => 'Profile updated successfully!'])
            : response()->json(['success' => false, 'message' => 'Failed to update profile.'], 500);
    }
}