<?php
session_start();

// On prépare la photo à afficher en haut à droite
if (isset($_SESSION['user_photo']) && !empty($_SESSION['user_photo']) && $_SESSION['user_photo'] != 'default.png') {
    $photo_affichage = 'uploads/' . $_SESSION['user_photo'];
} else {
    $photo_affichage = 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ÉVASION</title>
    <link rel="stylesheet" href="css/style.css?v=111">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <section class="hero">
			<nav>
				<div class="logo">ÉVASION</div>
				<link rel="shortcut icon" href="logo.ico" type="image/x-icon" />
				<div class="menu-toggle" id="mobile-menu">
                    <i class="fas fa-bars"></i>
                </div>
				<ul class="nav-links">
							<li><a href="#">Accueil</a></li>
							<li><a href="#">Offres</a></li>
							<li><a href="#">Conseiller(IA)</a></li>
							<li><a href="#">Destinations</a></li>
							<li><a href="#">Blog</a></li>
							<li><a href="#">Nous Contacter</a></li>
							
							<?php if(isset($_SESSION['user_id'])): ?>
                                <?php 
                                    // On compte combien de voyages sont dans le panier
                                    $nb_voyages = isset($_SESSION['panier']) ? count($_SESSION['panier']) : 0; 
                                ?>
								<li class="user-menu-container">
									<a href="#" class="user-icon" style="position: relative;">
                                        <?php if($nb_voyages > 0): ?>
                                            <span class="notif-dot"></span>
                                        <?php endif; ?>
                                        <img src="<?php echo $photo_affichage; ?>" alt="Avatar" class="nav-avatar">
                                    </a>
									
									<div class="user-dropdown">
										<div class="user-name">Bonjour, <?php echo htmlspecialchars($_SESSION['user_nom']); ?></div>
										<hr>
										<a href="profil.php"><i class="fas fa-id-badge"></i> Profil</a>
										<a href="voyages.php">
                                            <i class="fas fa-suitcase-rolling"></i> Mes voyages 
                                            <?php if($nb_voyages > 0): ?>
                                                <span class="notif-badge"><?php echo $nb_voyages; ?></span>
                                            <?php endif; ?>
                                        </a>
										<a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Se déconnecter</a>
									</div>
								</li>
							<?php else: ?>
								<li><a href="login.php" class="btn-login">Se connecter</a></li>
							<?php endif; ?>
				</ul>
			</nav>

        <div class="hero-content">
            <h1>Explore, Rêve, Voyage</h1>
        </div>

<div class="search-container">
    <div class="search-tabs">
        <button class="active" data-target="vol"><i class="fas fa-plane"></i> Vol</button>
        <button data-target="hotel"><i class="fas fa-hotel"></i> Hôtel</button>
        <button data-target="decouvrir"><i class="fas fa-magic"></i> Faites-moi découvrir</button>
		
    </div>
	
	<div class="search-inputs-bubble">
			
			<div id="vol" class="tab-content active-content">
				<div class="input-group">
					<label>Départ</label>
					<input type="text" class="custom-input city-autocomplete" value="Dubaï" placeholder="Ville ou aéroport" list="cities-list">
					<div class="sub-text">DXB</div>
				</div>
				<div class="separator-icon"><i class="fas fa-plane"></i></div>
				<div class="input-group">
					<label>Destination</label>
					<input type="text" class="custom-input city-autocomplete" value="Istanbul" placeholder="Ville ou aéroport" list="cities-list">
					<div class="sub-text">IST</div>
				</div>
				<div class="vertical-divider"></div>
				<div class="input-group">
					<label>Aller le</label>
					<input type="date" class="custom-input" value="2026-06-15">
				</div>
				<div class="separator-icon"><i class="far fa-calendar"></i></div>
				<div class="input-group">
					<label>Retour le</label>
					<input type="date" class="custom-input" value="2026-06-22">
				</div>
				
				<div class="search-btn-container">
					<button type="button" class="search-action-btn" onclick="alert('Recherche de vols en cours !')">
						<i class="fas fa-arrow-right"></i>
					</button>
				</div>
			</div>

			<div id="hotel" class="tab-content">
				<div class="input-group">
					<label>Destination</label>
					<input type="text" class="custom-input city-autocomplete" value="Paris" placeholder="Où allez-vous ?" list="cities-list">
					<div class="sub-text">France</div>
				</div>
				<div class="vertical-divider"></div>
				<div class="input-group">
					<label>Arrivée</label>
					<input type="date" class="custom-input" value="2026-07-10">
				</div>
				<div class="separator-icon"><i class="far fa-calendar"></i></div>
				<div class="input-group">
					<label>Départ</label>
					<input type="date" class="custom-input" value="2026-07-15">
				</div>
				<div class="vertical-divider"></div>
				<div class="input-group">
					<label>Voyageurs</label>
					<select class="custom-input select-input">
						<option value="1">1 Adulte</option>
						<option value="2" selected>2 Adultes</option>
						<option value="3">2 Adultes, 1 Enfant</option>
						<option value="4">Famille (4+)</option>
					</select>
				</div>
				
				<div class="search-btn-container">
					<button type="button" class="search-action-btn" onclick="alert('Recherche en cours !')">
						<i class="fas fa-arrow-right"></i>
					</button>
				</div>
			</div>

			<form id="decouvrir" class="tab-content discover-content" action="recherche_ia.php" method="POST">
				<input type="text" name="prompt_utilisateur" required placeholder="Ex: Un week-end romantique pas cher en Europe cet hiver..." class="discover-input">
				
					<button type="submit" class="btn-blue discover-btn">
						C'est parti ! <i class="fas fa-magic" style="margin-left: 5px;"></i>
					</button>
			</form>
			
			<datalist id="cities-list"></datalist>
		</div>
</div>
    </section>

    <section class="offers section-padding">
        <h2 class="section-title">Nos Dernières Offres</h2>
        
        <div class="cards-grid">
			<div class="card">
				<h3>Plages de Miami</h3>
				
				<div class="image-wrapper">
					<div class="card-image" style="background-image: url('https://images.unsplash.com/photo-1535498730771-e735b998cd64?w=500');"></div>
				</div>
				<div class="card-meta">
					<span class="tag chill">Détente</span>
					<span class="info">3 Jours, 1 Nuit</span>
					<span class="date">20.5.2023</span>
				</div>
				<div class="card-footer">
					<span class="price">850€ <small>/Personne</small></span>
					<form action="voyages.php" method="POST" style="margin: 0;">
                        <input type="hidden" name="action" value="ajouter">
                        <input type="hidden" name="titre" value="Plages de Miami">
                        <input type="hidden" name="prix" value="850">
                        <input type="hidden" name="image" value="https://images.unsplash.com/photo-1535498730771-e735b998cd64?w=500">
                        <button type="submit" class="btn-blue">Réserver</button>
                    </form>
				</div>
			</div>

            <div class="card">
                <h3>Japon</h3>
								<div class="image-wrapper">
                <div class="card-image" style="background-image: url('https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?w=500');"></div>
				</div>
                <div class="card-meta">
                    <span class="tag relax">Relaxation</span>
                    <span class="info">2 Jours, 1 Nuit</span>
                    <span class="date">20.5.2023</span>
                </div>
                <div class="card-footer">
                    <span class="price">1200€ <small>/Personne</small></span>
                    <form action="voyages.php" method="POST" style="margin: 0;">
                        <input type="hidden" name="action" value="ajouter">
                        <input type="hidden" name="titre" value="Japon">
                        <input type="hidden" name="prix" value="1700">
                        <input type="hidden" name="image" value="https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?w=500">
                        <button type="submit" class="btn-blue">Réserver</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <h3>Dubaï</h3>
								<div class="image-wrapper">
                <div class="card-image" style="background-image: url('https://www.walldisplay.ae/cdn/shop/files/dubai-panoramic-view-ii-walldisplay-wallpaper-dubai-abudhabi-33863884177558_1200x1200.jpg?v=1714458007');"></div>
				</div>
                <div class="card-meta">
                    <span class="tag chill">Détente</span>
                    <span class="info">2 Jours, 1 Nuit</span>
                    <span class="date">20.5.2023</span>
                </div>
                <div class="card-footer">
                    <span class="price">500€ <small>/Personne</small></span>
                    <form action="voyages.php" method="POST" style="margin: 0;">
                        <input type="hidden" name="action" value="ajouter">
                        <input type="hidden" name="titre" value="Dubaï">
                        <input type="hidden" name="prix" value="2500">
                        <input type="hidden" name="image" value="https://www.walldisplay.ae/cdn/shop/files/dubai-panoramic-view-ii-walldisplay-wallpaper-dubai-abudhabi-33863884177558_1200x1200.jpg?v=1714458000">
                        <button type="submit" class="btn-blue">Réserver</button>
                    </form>
                </div>
            </div>
             <div class="card">
                <h3>Taj Mahal</h3>
								<div class="image-wrapper">
                <div class="card-image" style="background-image: url('https://images.unsplash.com/photo-1564507592333-c60657eea523?w=500');"></div>
                </div>
				<div class="card-meta">
                    <span class="tag relax">Relaxation</span>
                    <span class="info">2 Jours, 1 Nuit</span>
                    <span class="date">20.5.2023</span>
                </div>
                <div class="card-footer">
                    <span class="price">350€ <small>/Personne</small></span>
                    <form action="voyages.php" method="POST" style="margin: 0;">
                        <input type="hidden" name="action" value="ajouter">
                        <input type="hidden" name="titre" value="Taj Mahal">
                        <input type="hidden" name="prix" value="600">
                        <input type="hidden" name="image" value="https://images.unsplash.com/photo-1564507592333-c60657eea523?w=500">
                        <button type="submit" class="btn-blue">Réserver</button>
                    </form>
                </div>
            </div>
             <div class="card">
                <h3>New York</h3>
								<div class="image-wrapper">
                <div class="card-image" style="background-image: url('https://images.unsplash.com/photo-1496442226666-8d4d0e62e6e9?w=500');"></div>
                </div>
				<div class="card-meta">
                    <span class="tag chill">Détente</span>
                    <span class="info">2 Jours, 1 Nuit</span>
                    <span class="date">20.5.2023</span>
                </div>
                <div class="card-footer">
                    <span class="price">500€ <small>/Personne</small></span>
                    <form action="voyages.php" method="POST" style="margin: 0;">
                        <input type="hidden" name="action" value="ajouter">
                        <input type="hidden" name="titre" value="New York">
                        <input type="hidden" name="prix" value="2300">
                        <input type="hidden" name="image" value="https://images.unsplash.com/photo-1496442226666-8d4d0e62e6e9?w=500">
                        <button type="submit" class="btn-blue">Réserver</button>
                    </form>
                </div>
            </div>
             <div class="card">
                <h3>Grèce</h3>
								<div class="image-wrapper">
                <div class="card-image" style="background-image: url('https://images.unsplash.com/photo-1533105079780-92b9be482077?w=500');"></div>
				</div>
                <div class="card-meta">
                    <span class="tag relax">Relaxation</span>
                    <span class="info">2 Jours, 1 Nuit</span>
                    <span class="date">20.5.2023</span>
                </div>
                <div class="card-footer">
                    <span class="price">950€ <small>/Personne</small></span>
                    <form action="voyages.php" method="POST" style="margin: 0;">
                        <input type="hidden" name="action" value="ajouter">
                        <input type="hidden" name="titre" value="Grèce">
                        <input type="hidden" name="prix" value="500">
                        <input type="hidden" name="image" value="https://images.unsplash.com/photo-1533105079780-92b9be482077?w=500">
                        <button type="submit" class="btn-blue">Réserver</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="ai-agent">
        <div class="ai-content">
            <img src="img/robot.png" alt="Illustration de l'Agent IA" class="ai-illustration">
            <h2>Découvrez le monde avec notre nouvel agent</h2>
            <p>Vous souhaitez explorer les paradis naturels de ce monde ? Trouvons ensemble la meilleure destination. Vous souhaitez explorer les paradis naturels de ce monde ? Trouvons ensemble la meilleure destination.</p>
            <button class="btn-wide-blue">Tester notre IA</button>
        </div>
    </section>

    <section class="destinations section-padding">
        <div class="dest-header">
            <h2>Les Destinations À Ne<br>Pas Manquer</h2>
            <p>Choisissez votre destination et contactez nos experts pour les meilleures offres !</p>
            <div class="slider-controls">
                <button><i class="fas fa-chevron-left"></i></button>
                <button class="active"><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
        <div class="dest-grid">
            <div class="dest-card" style="background-image: url('https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=500');">
                <span>Bali</span>
            </div>
            <div class="dest-card" style="background-image: url('https://t4.ftcdn.net/jpg/02/64/77/37/360_F_264773707_kR9T9uvhoo4YxrpXIVXwLHrFKT1PAaRk.jpg');">
                <span>Santorin</span>
            </div>
            <div class="dest-card" style="background-image: url('https://media.istockphoto.com/id/1156796325/fr/photo/vue-a%C3%A9rienne-de-la-m%C3%A9dina-bleue-de-la-ville-chefchaouen-maroc-afrique.jpg?s=612x612&w=0&k=20&c=43MyXDFYpPX8yAwzEDjsI83qwIQXgX3ItbPMYooZhOU=');">
                <span>Chefchaouen</span>
            </div>
        </div>
    </section>

    <section class="blog section-padding">
        <h2 class="section-title">À Lire Avant De Voyager</h2>
        
        <div class="blog-item">
            <img src="https://i0.wp.com/mcglobetrotteuse.com/wp-content/uploads/2021/01/KelingKing-Beach-scaled.jpeg?fit=2560%2C1920&ssl=1" alt="Nusa Penida">
            <div class="blog-text">
                <h3>GUIDE DE L'ÎLE DE NUSA PENIDA</h3>
                <p>La magnifique et exotique île de Nusa Penida se trouve à seulement 25 kilomètres de Bali, la destination touristique la plus célèbre d'Indonésie.</p>
                <a href="#" class="read-more"><i class="fas fa-arrow-right"></i> Lire Plus</a>
            </div>
        </div>

        <div class="blog-item reverse">
            <div class="blog-text">
                <h3>LES 17 MEILLEURES CASCADES D'INDONÉSIE</h3>
                <p>Créer un guide des cascades en Indonésie est difficile, car il y a tellement de cascades incroyables dans ce pays.</p>
                <a href="#" class="read-more"><i class="fas fa-arrow-right"></i> Lire Plus</a>
            </div>
            <img src="https://dynamic-media-cdn.tripadvisor.com/media/photo-o/17/97/b7/6f/tumpak-sewu-waterfalls.jpg?w=1200&h=-1&s=1" alt="Cascades">
        </div>
    </section>

    <section class="testimonials section-padding">
        <h2 class="section-title">Ce Que Nos Clients Disent De Nous</h2>
        <div class="testi-grid">
            <div class="testi-card fade">
                <div class="avatar"><img src="https://randomuser.me/api/portraits/men/32.jpg"></div>
                <h4>John Doe</h4>
                <p>Excepteur sint occaecat cupidatat non proident...</p>
            </div>
            <div class="testi-card main">
                <div class="avatar"><img src="https://randomuser.me/api/portraits/women/44.jpg"></div>
                <h4>Fatima Taylor</h4>
                <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit.</p>
            </div>
             <div class="testi-card fade">
                <div class="avatar"><img src="https://randomuser.me/api/portraits/women/68.jpg"></div>
                <h4>Sarah S.</h4>
                <p>Excepteur sint occaecat cupidatat non proident...</p>
            </div>
        </div>
<div class="dots">
    <span></span>
    <span class="active"></span>
    <span></span>
</div>
    </section>

    <div class="register-banner">
        <h2>Inscrivez-vous maintenant pour ce week-end et<br>économisez jusqu'à 10 % !</h2>
        <button class="btn-blue">Réserver</button>
    </div>

    <footer>
        <div class="footer-col">
            <h4>Restez Connectés</h4>
            <p>Adresse : Istanbul, Turquie</p>
            <p>Téléphone : (+90) 000 00 00</p>
        </div>
        <div class="footer-col">
            <h4>Travello</h4>
            <ul>
                <li>À Propos De Nous</li>
                <li>Carrières</li>
                <li>Collaboration</li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Sur Instagram</h4>
            <div class="insta-grid">
                <img src="https://images.unsplash.com/photo-1526772662003-6eb4dc482187?w=100">
                <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=100">
                <img src="https://images.unsplash.com/photo-1512453979798-5ea904ac6605?w=100">
                <img src="https://images.unsplash.com/photo-1540959733332-eab4deabeeaf?w=100">
                <img src="https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?w=100">
                <img src="https://images.unsplash.com/photo-1506929562872-bb421503ef21?w=100">
            </div>
        </div>
        <div class="footer-col">
            <h4>Newsletter</h4>
            <p>Recevez les dernières actualités et offres !</p>
            <input type="email" placeholder="Entrez votre adresse e-mail">
            <button class="btn-blue full-width">S'abonner</button>
        </div>
    </footer>

    <script src="js/main.js?=v5"></script>
</body>
</html>