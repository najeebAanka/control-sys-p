<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Validator;



class AdminController extends Controller
{



    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
    public function logout(Request $request)
    {
        Session::flush();
        
        Auth::logout();

        return redirect('dashboard/login');
        
    }

    function login(Request $request)
    {

    $request->validate([

        'email' => 'required',
        'password' => 'required|min:1',
        
    ]);


    $user = \App\Models\User::where('user_type', '=', '1')->first();

    // $hashedPassword = Hash::make($user->password); 
    //     if ($user->email == $request->email && Hash::check($request->password, $hashedPassword)) {
            if ($user->email == $request->email && Hash::check( $request->password, $user->password )) {
            Auth::login($user);
       
        return   redirect('dashboard/competitions');
    } else {
        return back()->with('error', 'Username and password are not correct.');

    }
}



}