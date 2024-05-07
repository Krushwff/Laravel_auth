<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthManager extends Controller
{
    function login()
    {
        if (Auth::check()) {
            return redirect()->intended(route('home'));
        }
        return view('login');
    }
    function registration(){
        if (Auth::check()) {
            return redirect()->intended(route('home'));
        }
        return view('registration');
    }
    function loginPost(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->intended(route('home'));
        }else{
            return redirect(route('login'))->with('error', 'Login false are not validate');
        }
    }
    function registrationPost(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['password'] = Hash::make($request->password);
        $user = User::create($data);
        if (!$user){
            return redirect()->intended(route('registration'))->with('error', 'Registration false');
        }
        return redirect()->intended(route('login'))->with("success", "Registration success");
    }

    function logout(){
        Session::flush();
        Auth::logout();
        return redirect()->intended(route('login'));
    }
}
