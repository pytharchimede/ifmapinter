<?php

namespace App\Controllers;

class HomeController
{
    public function index(): string
    {
        $title = 'IFMAP – Accueil';
        return view('home', compact('title'));
    }
}
