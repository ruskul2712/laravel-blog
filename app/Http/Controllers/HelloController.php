<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class HelloController extends Controller
{
    public function index()
    {
        $user = User::firstOrFail();

        return view('posts.about', compact('user'));
    }
}
