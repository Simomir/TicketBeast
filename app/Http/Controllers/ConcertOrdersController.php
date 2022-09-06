<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConcertOrdersController extends Controller
{
    public function store() {
        return response()->json([], 201);
    }
}
