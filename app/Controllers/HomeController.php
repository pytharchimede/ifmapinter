<?php

namespace App\Controllers;

class HomeController
{
    public function index(): string
    {
        $title = 'IFMAP – Accueil';
        // Chargement des données BDD
        $news = db()->query('SELECT * FROM news ORDER BY COALESCE(published_at, created_at) DESC LIMIT 3')->fetchAll();
        $programmes = db()->query('SELECT * FROM programmes ORDER BY id DESC LIMIT 3')->fetchAll();
        $formations = db()->query('SELECT * FROM formations ORDER BY id DESC LIMIT 4')->fetchAll();
        $partners = db()->query('SELECT * FROM partners ORDER BY id DESC LIMIT 8')->fetchAll();
        return view('home', compact('title', 'news', 'programmes', 'formations', 'partners'));
    }
}
