<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class confirmPasswordController extends Controller
{
    public function showConfirmPasswordForm()
{

    return view('auth.confirmPassword');
}

public function confirmPassword(Request $request)
{
    $request->validate([
        'password' => 'required', // Use your password validation rules
    ]);

    if (Hash::check($request->password, auth()->user()->password)) {
        session(['confirmed_password' => true]);
        // Get the intended URL from the session
        $intendedUrl = Session::get('intended_url', '/');
        Session::forget('intended_url'); // Clear the stored URL

        return redirect($intendedUrl);
    } else {
        return redirect(Session::get('prev_url', '/'))->with('error', "Wrong Password");
    }
}
}
