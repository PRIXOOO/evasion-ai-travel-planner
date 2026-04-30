<?php
session_start();

// Si l'utilisateur n'est pas connecté, on le force à se connecter pour réserver
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Initialisation du panier s'il n'existe pas encore
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

// --- GESTION DE L'AJOUT AU PANIER ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'ajouter') {
    $nouveau_voyage = [
        'titre' => $_POST['titre'],
        'prix' => $_POST['prix'],
        'image' => $_POST['image'],
        // On génère un ID unique pour pouvoir le supprimer facilement plus tard
        'id_reservation' => uniqid() 
    ];
    // On ajoute le voyage dans le tableau mémoire de la session
    $_SESSION['panier'][] = $nouveau_voyage;
    
    // On redirige pour éviter de rajouter le voyage 2 fois si on actualise la page
    header("Location: voyages.php");
    exit();
}

// --- GESTION DE LA SUPPRESSION D'UN VOYAGE ---
if (isset($_GET['supprimer'])) {
    $id_a_supprimer = $_GET['supprimer'];
    // On cherche le voyage et on l'enlève du tableau
    foreach ($_SESSION['panier'] as $index => $voyage) {
        if ($voyage['id_reservation'] == $id_a_supprimer) {
            unset($_SESSION['panier'][$index]);
        }
    }
    // On réorganise les index du tableau proprement
    $_SESSION['panier'] = array_values($_SESSION['panier']);
    header("Location: voyages.php");
    exit();
}

// --- CALCUL DU TOTAL ---
$total = 0;
foreach ($_SESSION['panier'] as $voyage) {
    $total += intval($voyage['prix']);
}

// Préparation de la photo de profil (comme sur l'accueil)
if (isset($_SESSION['user_photo']) && !empty($_SESSION['user_photo']) && $_SESSION['user_photo'] != 'default.png') {
    $photo_affichage = 'uploads/' . $_SESSION['user_photo'];
} else {
    $photo_affichage = 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png';
}
$nb_voyages = count($_SESSION['panier']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<link rel="shortcut icon" href="logo.ico" type="image/x-icon" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Voyages - ÉVASION</title>
    <link rel="stylesheet" href="css/style.css?v=3"> <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="cart-page-bg">

    <nav style="background: var(--primary-blue); padding: 15px 5%; width: 100%; margin-bottom: 40px;">
        <div class="logo"><a href="index.php">ÉVASION</a></div>
        <ul class="nav-links">
            <li><a href="index.php">Accueil</a></li>
            <li class="user-menu-container">
                <a href="#" class="user-icon">
                    <?php if($nb_voyages > 0): ?><span class="notif-dot"></span><?php endif; ?>
                    <img src="<?php echo $photo_affichage; ?>" alt="Avatar" class="nav-avatar">
                </a>
                <div class="user-dropdown">
                    <div class="user-name">Bonjour, <?php echo htmlspecialchars($_SESSION['user_nom']); ?></div>
                    <hr>
                    <a href="profil.php"><i class="fas fa-id-badge"></i> Profil</a>
                    <a href="voyages.php">
                        <i class="fas fa-suitcase-rolling"></i> Mes voyages
                        <?php if($nb_voyages > 0): ?><span class="notif-badge"><?php echo $nb_voyages; ?></span><?php endif; ?>
                    </a>
                    <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Se déconnecter</a>
                </div>
            </li>
        </ul>
    </nav>

    <div class="cart-container">
        <h2 class="cart-title"><i class="fas fa-shopping-cart"></i> Mon Panier de Réservations</h2>

        <?php if ($nb_voyages == 0): ?>
            <div class="empty-cart">
                <i class="fas fa-box-open" style="font-size: 50px; margin-bottom: 20px; color: #ccc;"></i>
                <h2>Votre valise est vide !</h2>
                <p>Découvrez nos offres et commencez à préparer votre prochain voyage.</p>
                <br>
                <a href="index.php" class="btn-blue">Voir les destinations</a>
            </div>
        <?php else: ?>
            
            <div class="cart-items-list">
                <?php foreach ($_SESSION['panier'] as $voyage): ?>
                    <div class="cart-item">
                        <img src="<?php echo htmlspecialchars($voyage['image']); ?>" alt="Destination">
                        <div class="cart-item-info">
                            <div class="cart-item-title"><?php echo htmlspecialchars($voyage['titre']); ?></div>
                            <div style="color: #777; font-size: 13px;">Billet aller-retour + Hôtel</div>
                        </div>
                        <div class="cart-item-price"><?php echo htmlspecialchars($voyage['prix']); ?> €</div>
                        <a href="voyages.php?supprimer=<?php echo $voyage['id_reservation']; ?>" class="btn-remove" title="Retirer ce voyage"><i class="fas fa-trash"></i></a>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="cart-footer">
                <div class="cart-total">Total à régler : <?php echo $total; ?> €</div>
                <button class="btn-pay" onclick="alert('Redirection vers la passerelle de paiement (Stripe/PayPal) !')">Procéder au paiement <i class="fas fa-credit-card" style="margin-left: 10px;"></i></button>
            </div>

        <?php endif; ?>
    </div>

</body>
</html>