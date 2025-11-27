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
        $events = db()->query('SELECT * FROM events ORDER BY event_date ASC LIMIT 4')->fetchAll();
        $testimonials = db()->query('SELECT * FROM testimonials ORDER BY id DESC LIMIT 3')->fetchAll();
        // Stats
        $stats = [
            'programmes' => (int)db()->query('SELECT COUNT(*) FROM programmes')->fetchColumn(),
            'formations' => (int)db()->query('SELECT COUNT(*) FROM formations')->fetchColumn(),
            'partners'   => (int)db()->query('SELECT COUNT(*) FROM partners')->fetchColumn(),
            'news'       => (int)db()->query('SELECT COUNT(*) FROM news')->fetchColumn(),
        ];
        return view('home', compact('title', 'news', 'programmes', 'formations', 'partners', 'events', 'testimonials', 'stats'));
    }
}
