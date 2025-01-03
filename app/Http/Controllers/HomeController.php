<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        var_dump($_REQUEST['laravel_session']);exit;
    }
}
