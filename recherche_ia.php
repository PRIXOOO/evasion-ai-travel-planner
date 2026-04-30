<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['prompt_utilisateur'])) {
    header("Location: index.php");
    exit();
}

$prompt_utilisateur = htmlspecialchars($_POST['prompt_utilisateur']);

// --- GESTION DE LA PHOTO DE PROFIL ---
$photo_affichage = 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png'; // Fallback
if (isset($_SESSION['user_photo']) && !empty($_SESSION['user_photo']) && $_SESSION['user_photo'] !== 'default.png') {
    if (file_exists('uploads/' . $_SESSION['user_photo'])) {
        $photo_affichage = 'uploads/' . $_SESSION['user_photo'];
    }
}

// --- 1. CONFIGURATION DE L'API MISTRAL ---
$api_key = "1h2UlWDMvlU2T6CYjsee0V9hpAVksBux";

$system_prompt = "Tu es un agent de voyage expert.
Invente un voyage parfait et cohérent (avec des dates logiques). 
Tu DOIS retourner UNIQUEMENT un objet JSON valide, sans aucun autre texte avant ou après.
Voici le format exact attendu :
{
    \"titre\": \"Nom du voyage (ex: Séjour magique à Bali)\",
    \"pays\": \"Pays de destination\",
    \"description\": \"Une phrase d'accroche qui donne très envie\",
    \"prix\": 1200, 
    \"date_depart\": \"01.06.2024\",
    \"date_retour\": \"08.06.2024\",
    \"jours\": 8,
    \"nuits\": 7,
    \"type\": \"relaxation\"
}";

$data = [
    "model" => "mistral-large-latest",
    "response_format" => ["type" => "json_object"],
    "messages" => [
        ["role" => "system", "content" => $system_prompt],
        ["role" => "user", "content" => $prompt_utilisateur]
    ]
];

// --- 2. REQUÊTE CURL ---
$url = "https://api.mistral.ai/v1/chat/completions";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $api_key"
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$is_ajax = (isset($_POST['is_ajax']) && $_POST['is_ajax'] === 'true');

// =========================================================
// 🚨 GESTION DE L'ERREUR (S'exécute et arrête tout si ça plante)
// =========================================================
if ($http_code != 200) {
    $erreur_mistral = json_decode($response, true);
    $message_erreur_api = isset($erreur_mistral['message']) ? $erreur_mistral['message'] : "Erreur Inconnue de Mistral";

    if ($is_ajax) {
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(["error" => "Mistral a refusé : " . $message_erreur_api]);
    } else {
        echo "<div style='text-align:center; padding: 50px; font-family: sans-serif;'>";
        echo "<h2>Oups, une erreur est survenue !</h2>";
        echo "<p>Raison : " . htmlspecialchars($message_erreur_api) . "</p>";
        echo "<a href='index.php' style='color: #1C8CB7;'>Retour à l'accueil</a>";
        echo "</div>";
    }
    exit(); // FIN DU SCRIPT ICI EN CAS D'ERREUR.
}


// =========================================================
// ✅ SI TOUT VA BIEN : ON TRAITE LES DONNÉES
// =========================================================
$result = json_decode($response, true);
$voyage_json_string = $result['choices'][0]['message']['content'];

// Si c'est JS qui a fait la requête, on renvoie juste le texte et on coupe
if ($is_ajax) {
    header('Content-Type: application/json');
    echo $voyage_json_string;
    exit(); // FIN DU SCRIPT POUR AJAX.
}

// Sinon, on prépare les variables pour le HTML classique
$voyage = json_decode($voyage_json_string, true);

// Pixabay
$mot_cle = urlencode($voyage['pays'] . " " . $voyage['titre']);
$pixabay_key = "49018449-cd8b2841f4cdbfddb5ab3d1a3";
$pixabay_url = "https://pixabay.com/api/?key={$pixabay_key}&q={$mot_cle}&image_type=photo&orientation=horizontal&per_page=3";

$ch_img = curl_init($pixabay_url);
curl_setopt($ch_img, CURLOPT_RETURNTRANSFER, true);
$response_img = curl_exec($ch_img);
curl_close($ch_img);

$data_img = json_decode($response_img, true);
$image_url = "https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?w=800"; // Fallback

if (isset($data_img['hits']) && count($data_img['hits']) > 0) {
    $image_url = $data_img['hits'][array_rand($data_img['hits'])]['largeImageURL'];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ton Voyage Généré - ÉVASION</title>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            /* L'image de plage de ton accueil, fixée en fond avec un léger voile sombre */
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('https://images.unsplash.com/photo-1499793983690-e29da59ef1c2?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80') center/cover fixed !important;
            margin: 0;
            padding: 0;
        }
        
        /* On force la navigation en blanc comme sur l'accueil */
        nav { padding: 30px 10%; display: flex; justify-content: space-between; align-items: center; }
        .logo { color: white !important; font-size: 2rem; font-weight: 700; }
        .nav-links a { color: white !important; }
        .nav-links a:hover { color: #ddd !important; }
        
        /* Les titres de la page en blanc avec une belle ombre */
        .titre-blanc { 
            color: white !important; 
            text-shadow: 0 4px 15px rgba(0,0,0,0.6); 
        }
        
        /* Menu profil qui reste lisible */
        .user-dropdown span, .user-dropdown a { color: #333 !important; }
        .profile-pic { border: 2px solid white; }
    </style>
</head>
<body>

    <nav>
        <div class="logo">ÉVASION</div>
        <ul class="nav-links">
            <li><a href="index.php">Accueil</a></li>
            <li><a href="index.php#destinations">Destinations</a></li>
            <li><a href="index.php#services">Services</a></li>

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

        <div class="menu-toggle" id="mobile-menu">
            <i class="fas fa-bars" style="color: white;"></i>
        </div>
    </nav>

    <div class="container" style="max-width: 900px; margin: 20px auto 80px auto; padding: 0 20px;">
        
        <a href="index.php" style="background: rgba(255,255,255,0.2); color: white; padding: 10px 20px; border-radius: 25px; text-decoration: none; margin-bottom: 30px; display: inline-block; backdrop-filter: blur(5px); border: 1px solid rgba(255,255,255,0.3); font-weight: 500;">
            <i class="fas fa-arrow-left"></i> Retour à la recherche
        </a>

        <div style="text-align: center; margin-bottom: 40px;">
            <h1 class="titre-blanc" style="font-size: 3rem; margin-bottom: 10px;">Votre Voyage Sur-Mesure</h1>
            <p class="titre-blanc" style="font-size: 1.2rem; font-weight: 400;">L'IA a concocté cette destination pour vous : <b>"<?php echo htmlspecialchars($prompt_utilisateur); ?>"</b></p>
        </div>

        <div class="ia-result-card" style="background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 20px 50px rgba(0,0,0,0.3);">
            
            <div style="height: 350px; background-image: url('<?php echo htmlspecialchars($image_url); ?>'); background-size: cover; background-position: center; position: relative;">
                <div style="position: absolute; bottom: 0; left: 0; width: 100%; padding: 30px 20px; background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                    <div style="display: inline-block; background: var(--primary-blue); color: white; padding: 5px 15px; border-radius: 20px; font-size: 0.9rem; margin-bottom: 10px; font-weight: 500; text-transform: uppercase;">
                        <?php echo htmlspecialchars($voyage['type']); ?>
                    </div>
                    <h2 style="color: white; font-size: 2.2rem; margin-bottom: 5px; text-shadow: none;"><?php echo htmlspecialchars($voyage['titre']); ?></h2>
                    <div style="color: #ddd; font-size: 1.1rem;"><i class="fas fa-map-marker-alt" style="color: #ff6b6b;"></i> <?php echo htmlspecialchars($voyage['pays']); ?></div>
                </div>
            </div>

            <div style="padding: 30px;">
                <p style="font-size: 1.15rem; color: #444; line-height: 1.6; margin-bottom: 30px; font-style: italic;">
                    "<?php echo htmlspecialchars($voyage['description']); ?>"
                </p>

                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px; background: #f8f9fa; padding: 20px; border-radius: 15px;">
                    <div style="text-align: center;">
                        <i class="far fa-clock" style="font-size: 24px; color: #1C8CB7; margin-bottom: 10px;"></i>
                        <div style="font-weight: 600; color: #333;"><?php echo htmlspecialchars($voyage['jours']); ?> Jours</div>
                        <div style="color: #777; font-size: 0.9rem;"><?php echo htmlspecialchars($voyage['nuits']); ?> Nuits</div>
                    </div>
                    <div style="text-align: center; border-left: 1px solid #ddd; border-right: 1px solid #ddd;">
                        <i class="far fa-calendar-alt" style="font-size: 24px; color: #1C8CB7; margin-bottom: 10px;"></i>
                        <div style="font-weight: 600; color: #333;">Départ</div>
                        <div style="color: #777; font-size: 0.9rem;"><?php echo htmlspecialchars($voyage['date_depart']); ?></div>
                    </div>
                    <div style="text-align: center;">
                        <i class="far fa-calendar-check" style="font-size: 24px; color: #1C8CB7; margin-bottom: 10px;"></i>
                        <div style="font-weight: 600; color: #333;">Retour</div>
                        <div style="color: #777; font-size: 0.9rem;"><?php echo htmlspecialchars($voyage['date_retour']); ?></div>
                    </div>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 20px; border-top: 1px solid #eee;">
                    <div>
                        <span style="font-size: 2.2rem; font-weight: 700; color: #222;"><?php echo htmlspecialchars($voyage['prix']); ?>€</span>
                        <span style="color: #777; font-size: 1rem;">/ Personne</span>
                    </div>
                    
                    <form action="voyages.php" method="POST" style="margin: 0;">
                        <input type="hidden" name="action" value="ajouter">
                        <input type="hidden" name="titre" value="<?php echo htmlspecialchars($voyage['titre']); ?>">
                        <input type="hidden" name="prix" value="<?php echo htmlspecialchars($voyage['prix']); ?>">
                        <input type="hidden" name="image" value="<?php echo htmlspecialchars($image_url); ?>">
                        <button type="submit" class="btn-blue" style="padding: 15px 35px; font-size: 1.1rem; border-radius: 30px; transition: transform 0.2s, box-shadow 0.2s;">
                            Réserver ce voyage <i class="fas fa-suitcase-rolling" style="margin-left: 8px;"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <script src="js/main.js"></script>

</body>
</html>