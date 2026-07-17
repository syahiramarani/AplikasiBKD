<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VeruficationController extends Controller
{
    public function index(){
        return view('verufication.index');
    }
}
