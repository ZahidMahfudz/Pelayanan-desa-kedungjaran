<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    function admin(){
        echo 'ini halaman admin';
        echo '<a href="/logout"> exit</a>';
    }

    // function operator(){
    //     return view('operator.Dashboard');
    // }
}
