<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SesiController extends Controller
{
    function index(){
        return view('login');
    }

    function login(Request $request){
        $request->validate([
            'username'=>'required',
            'password'=>'required'
        ],
        [
            'username.required'=>'username wajib diisi',
            'password.required'=>'password wajib diisi'
        ]);

        $infologin = [
            'username'=>$request->username,
            'password'=>$request->password
        ];

        if(Auth::attempt($infologin)){
            if(Auth::user()->role == 'admin'){
                return redirect('/user/admin');
            }
            if(Auth::user()->role == 'operator'){
                return redirect('/user/operator/dashboard');
            }
        }else{
            return redirect()->back()->withErrors('Username dan password salah')->withInput();
        }
    }

    public function terobos(){
        echo 'Anda tidak diperbolehkan menerobos';
        echo '<a href="/logout"> exit</a>';
    }

    public function logout(){
        Auth::logout();
        return redirect('');
    }
    
}
