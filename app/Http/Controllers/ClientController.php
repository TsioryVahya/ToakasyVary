<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function nombreClients()
    {
        $total = Client::count(); // total des lignes dans la table clients

        return response()->json(['nombre_clients' => $total]);
    }
}
