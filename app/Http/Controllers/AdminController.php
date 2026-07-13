<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;


class AdminController extends Controller
{
    public function adminPanel(){
        if(!Gate::allows('admin')){
            abort(403);
        }
        return 'Добро пожаловать в админ-панель!';
    }
}
