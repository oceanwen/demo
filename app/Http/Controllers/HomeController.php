<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        var_dump(123123);exit;
        return view('welcome');
    }
}
