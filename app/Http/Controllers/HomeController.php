<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;

class HomeController extends Controller
{


    public function index()
    {
        $nombreClients = Client::count();

        // Tu peux aussi calculer la variation si besoin :
        // $variation = ...;

        return view('home', compact('nombreClients'));
    }
}
