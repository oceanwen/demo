<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        dump($_SERVER['REMOTE_ADDR']);exit
    }
}
