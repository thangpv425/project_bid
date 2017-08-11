<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserController extends Controller {

    public function index() {
        return view('home');
    }

    public function __construct() {
        $this->middleware('auth');
    }
}
