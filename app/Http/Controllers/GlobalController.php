<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GlobalController extends Controller
{
    public function changeTheme(Request $request) {

        if($request->theme) {

            if($request->theme == 'light') {
                session(['theme' => 'dark']);
            }
            else {
                session(['theme' => 'light']);
            }

        }

    }
}
