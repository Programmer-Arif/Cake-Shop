<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function signupSubmit(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        ]);
        
        if($validator->passes()){
            $user = User::create($request->all());
            if($user){
                return redirect()->route('login')->with('success','You have registered successifully');
            } 
        }else{
            return redirect()->route('signup')->withInput()->withErrors($validator);
        }
       
    }
    public function loginSubmit(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        if($validator->passes()){
           if(Auth::attempt($request->only('email','password'))){
                return redirect()->route('dashboard');
            }else{
               return redirect()->route('login')->with('error','Either Email or Password is Incorrect');
           }
        }else{
            return redirect()->route('login')->withInput()->withErrors($validator);
        }
    }

    public function dashboard(){
        return view('dashboard');
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('login');
    }
}
