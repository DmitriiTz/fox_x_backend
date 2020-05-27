<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function help() {
        $pageName = 'Help';
        return view('help', compact('pageName'));
    }
}
