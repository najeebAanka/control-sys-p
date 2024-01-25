<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class UsersController extends Controller {

    //



    public function logout(Request $request) {
         //   Auth::logout();
          $request->user()->fcm_token = "";
          $request->user()->save();
        $request->user()->token()->revoke(); // CORRRRRRRRRRRRRRECT
        return response()->json( ['message'=> "Logged out successfully"] ,200);
    }

    function login(Request $request) {
         $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required|min:1'
        ]);

          if ($validator->fails()) {
         
             return response()->json(['error' => $this->failedValidation($validator)], 401);
        }

        if (Auth::attempt(['email' => request('username'), 'password' => request('password')])) {

            $user = Auth::user();
            $user->token = $user->createToken('authToken')->accessToken;
            $user->direct_forward_to_active_competition = true;
            return response()->json($user);
        } else {
            return response()->json(['error' => 'Data is not correct.'], 401);
        }
    }
    
       function loginViaCode(Request $request) {
         $validator = Validator::make($request->all(), [
            'code' => 'required',
        ]);

          if ($validator->fails()) {
         
             return response()->json(['error' => $this->failedValidation($validator)], 401);
        }
        $user = User::where('qr_code' ,$request->code)->first();
        if($user){

            Auth::login($user);
            $user = Auth::user();
            $user->name = $user->name();
            $user->token = $user->createToken('authToken')->accessToken;
            return response()->json($user);
      
        
        }else {
            return response()->json(['error' => 'Data is not correct.'], 401);
        }
    }
    
        public function update(Request $request) {



        $user = Auth::user();

        if ($request->has('name'))
            $user->name = $request->name;

    if ($request->has('fcm_token'))
            $user->fcm_token = $request->fcm_token;



        $user->save();

     return response()->json(['message'=> "Updated."], 200);
    }
    
    

//    function signup(Request $request) {
//         $validator = Validator::make($request->all(), [
//            'name' => 'required',
//            'email' => 'required|unique:users,email',
//            'password' => 'required|min:1'
//        ]);
//
//          if ($validator->fails()) {
//         
//             return response()->json(['error' => $this->failedValidation($validator)], 401);
//        }
//        
//        $u = new User();
//        $u->name = request('name');
//        $u->email = request('email');
//        $u->password = bcrypt(request('password'));
//        $u->save();
//
//        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
//
//            $user = Auth::user();
//            $user->token = $user->createToken('authToken')->accessToken;
//            return response()->json($user);
//        } else {
//            return response()->json(['error' => 'Data is not correct.'], 401);
//        }
//    }

}