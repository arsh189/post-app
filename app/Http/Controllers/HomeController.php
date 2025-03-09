<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    //
    public function dashboard(){
        return view('dashboard');
    }

    public function uploadProfilePicture(Request $request)
    {
        if ($request->hasFile('cropped_image')) {
            $user = Auth::user();
            $image = $request->file('cropped_image');
    
            // Delete old profile picture if exists
            if ($user->profile_picture && Storage::exists($user->profile_picture)) {
                Storage::delete($user->profile_picture);
            }
    
            // Save new profile picture
            $path = $image->store('profile_pictures', 'public');
            $user->profile_picture = $path;
            $user->save();
    
            return response()->json([
                'success' => true,
                'image_url' => asset('storage/' . $path)
            ]);
        }
    
        return response()->json(['success' => false], 400);
    }
    
}
