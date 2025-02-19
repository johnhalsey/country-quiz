<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

abstract class Controller
{
    public function index(): \Inertia\Response
    {
        return Inertia::render('Home');
    }
}
