<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPUnit\Framework\Constraint\Operator;

class OperatController extends Controller
{
    public function showKesekretariatan(){
        $title = 'Kesekretariatan';
        return view('operator.kesekretariatan', compact('title'));
    }

    public function showPenduduk(){
        $title = 'Data Penduduk';
        return view('operator.Penduduk', compact('title'));
    }

    public function showdashboard(){
        $title = 'Dashboard';
        return view('operator.Dashboard', compact('title'));
    }
}
