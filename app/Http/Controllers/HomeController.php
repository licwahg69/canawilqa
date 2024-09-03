<?php

namespace App\Http\Controllers;



class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        session(['menupopup_id' => 0]);
        session(['submenupopup_id' => 0]);

        return view('home');
    }
}
