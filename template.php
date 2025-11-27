<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Institut IFMAP</title>
    <style>
        /* Reset & base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            line-height: 1.6;
            color: #333;
            scroll-behavior: smooth;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        img {
            max-width: 100%;
            display: block;
        }

        /* Couleurs IFMAP */
        :root {
            --ifmap-blue: #003d7a;
            --ifmap-light: #f4f4f4;
            --ifmap-accent: #0073e6;
            --ifmap-dark: #001f3f;
        }

        /* Menu */
        header {
            position: sticky;
            top: 0;
            background: white;
            z-index: 999;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
        }

        nav .logo {
            font-weight: bold;
            color: var(--ifmap-blue);
            font-size: 1.5rem;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 1.5rem;
        }

        nav ul li a {
            font-weight: 500;
            color: var(--ifmap-dark);
        }

        nav ul li a:hover {
            color: var(--ifmap-accent);
        }

        /* Hero */
        .hero {
            position: relative;
            background: url('https://images.unsplash.com/photo-1564869739896-c5d7ce046db4?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxfDB8MXxyYW5kb218MHx8bGVhcm5pbmclMjBmb3JtfHx8fHx8MTY5MTI2NTk5OQ&ixlib=rb-4.0.3&q=80&w=1920') no-repeat center/cover;
            height: 90vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
        }

        .hero::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
        }

        .hero-content {
            position: relative;
            max-width: 900px;
            padding: 0 1rem;
        }

        .hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            animation: fadeInUp 1s ease forwards;
            opacity: 0;
        }

        .hero p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            animation: fadeInUp 1.5s ease forwards;
            opacity: 0;
        }

        .hero .cta {
            padding: 0.75rem 2rem;
            background: var(--ifmap-accent);
            color: white;
            font-weight: bold;
            border-radius: 4px;
            transition: 0.3s;
        }

        .hero .cta:hover {
            background: var(--ifmap-blue);
        }

        /* Sections */
        section {
            padding: 5rem 2rem;
        }

        .section-title {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-title h2 {
            font-size: 2.5rem;
            color: var(--ifmap-blue);
            margin-bottom: 0.5rem;
        }

        .section-title p {
            color: #555;
            font-size: 1.1rem;
        }

        /* Programmes grid */
        .grid {
            display: grid;
            gap: 2rem;
        }

        .grid-3 {
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        }

        .card {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .card h3 {
            color: var(--ifmap-blue);
            margin-bottom: 0.5rem;
        }

        .card p {
            color: #555;
            font-size: 0.95rem;
        }

        /* Carousel */
        .carousel {
            position: relative;
            overflow: hidden;
        }

        .carousel-track {
            display: flex;
            transition: transform 0.5s ease;
        }

        .carousel-item {
            min-width: 100%;
            flex-shrink: 0;
            padding: 2rem;
            box-sizing: border-box;
        }

        .carousel-item img {
            border-radius: 8px;
        }

        /* Carousel controls */
        .carousel-controls {
            position: absolute;
            top: 50%;
            width: 100%;
            display: flex;
            justify-content: space-between;
            transform: translateY(-50%);
        }

        .carousel-controls button {
            background: rgba(0, 0, 0, 0.5);
            border: none;
            color: white;
            font-size: 2rem;
            padding: 0.2rem 0.7rem;
            cursor: pointer;
            border-radius: 50%;
        }

        .carousel-controls button:hover {
            background: rgba(0, 0, 0, 0.7);
        }

        /* Footer */
        footer {
            background: var(--ifmap-blue);
            color: white;
            padding: 2rem;
        }

        footer a {
            color: white;
            margin-right: 1rem;
        }

        /* Animations */
        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media(max-width:768px) {
            nav ul {
                flex-direction: column;
                gap: 1rem;
            }

            .hero h1 {
                font-size: 2rem;
            }

            .hero p {
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>

    <!-- Header -->
    <header>
        <nav>
            <div class="logo">IFMAP</div>
            <ul>
                <li><a href="#apropos">À propos</a></li>
                <li><a href="#programmes">Programmes</a></li>
                <li><a href="#instituts">Instituts</a></li>
                <li><a href="#actualites">Actualités</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </nav>
    </header>

    <!-- Hero -->
    <section class="hero">
        <div class="hero-content">
            <h1>Institut IFMAP des Métiers Professionnels</h1>
            <p>Former les talents de demain dans un environnement innovant et dynamique</p>
            <a href="#programmes" class="cta">Découvrez nos programmes</a>
        </div>
    </section>

    <!-- À propos -->
    <section id="apropos" style="background:var(--ifmap-light);">
        <div class="section-title">
            <h2>À propos de IFMAP</h2>
            <p>L'Institut IFMAP propose des formations de qualité pour les professionnels et étudiants ambitieux.</p>
        </div>
    </section>

    <!-- Programmes -->
    <section id="programmes">
        <div class="section-title">
            <h2>Nos Programmes</h2>
            <p>Des parcours adaptés à chaque profil</p>
        </div>
        <div class="grid grid-3">
            <div class="card">
                <h3>Bachelor Professionnel</h3>
                <p>Programme undergraduate pour étudiants motivés et curieux.</p>
            </div>
            <div class="card">
                <h3>Masters & Grande École</h3>
                <p>Programmes de master pour former les leaders de demain.</p>
            </div>
            <div class="card">
                <h3>MBA Professionnel</h3>
                <p>Formation pour cadres et dirigeants souhaitant se perfectionner.</p>
            </div>
            <div class="card">
                <h3>Programme PhD</h3>
                <p>Doctorat en sciences de gestion et management.</p>
            </div>
            <div class="card">
                <h3>Executive Education</h3>
                <p>Formations continues pour entreprises et professionnels.</p>
            </div>
            <div class="card">
                <h3>IFMAP Online</h3>
                <p>Programmes flexibles et accessibles en ligne.</p>
            </div>
        </div>
    </section>

    <!-- Instituts -->
    <section id="instituts" style="background:var(--ifmap-light);">
        <div class="section-title">
            <h2>Nos Instituts & Centres</h2>
            <p>Exploration et recherche dans des domaines spécialisés</p>
        </div>
        <div class="grid grid-3">
            <div class="card">
                <h3>Centre IFMAP Énergie & Industrie</h3>
                <p>Analyse et innovations dans le secteur industriel et énergétique.</p>
            </div>
            <div class="card">
                <h3>Institut IFMAP Finance & Management</h3>
                <p>Développement de compétences pour le secteur financier.</p>
            </div>
            <div class="card">
                <h3>Centre Innovation & Entrepreneuriat</h3>
                <p>Accompagnement des start-ups et projets innovants.</p>
            </div>
        </div>
    </section>

    <!-- Actualités -->
    <section id="actualites">
        <div class="section-title">
            <h2>Actualités</h2>
            <p>Toutes les dernières informations de l'IFMAP</p>
        </div>
        <div class="carousel">
            <div class="carousel-track">
                <div class="carousel-item">
                    <img src="https://images.unsplash.com/photo-1581091870624-1ecf5170ff4b?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&w=1200&q=80" alt="Actualité 1">
                </div>
                <div class="carousel-item">
                    <img src="https://images.unsplash.com/photo-1600891964599-f61ba0e24092?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&w=1200&q=80" alt="Actualité 2">
                </div>
                <div class="carousel-item">
                    <img src="https://images.unsplash.com/photo-1519455953755-af066f52f1b4?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&w=1200&q=80" alt="Actualité 3">
                </div>
            </div>
            <div class="carousel-controls">
                <button id="prev">&#10094;</button>
                <button id="next">&#10095;</button>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact">
        <p>Institut IFMAP © 2025</p>
        <div>
            <a href="#">Facebook</a>
            <a href="#">LinkedIn</a>
            <a href="#">Instagram</a>
        </div>
    </footer>

    <!-- JS Carousel -->
    <script>
        const track = document.querySelector('.carousel-track');
        const items = document.querySelectorAll('.carousel-item');
        const prevBtn = document.getElementById('prev');
        const nextBtn = document.getElementById('next');
        let index = 0;

        function showSlide(i) {
            if (i < 0) index = items.length - 1;
            else if (i >= items.length) index = 0;
            else index = i;
            track.style.transform = 'translateX(-' + (100 * index) + '%)';
        }

        prevBtn.addEventListener('click', () => showSlide(index - 1));
        nextBtn.addEventListener('click', () => showSlide(index + 1));
        setInterval(() => showSlide(index + 1), 5000);
    </script>

</body>

</html>