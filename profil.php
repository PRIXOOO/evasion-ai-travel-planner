<?php
session_start();

// Si l'utilisateur n'est pas connecté, on le renvoie à la page de connexion
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// --- CONNEXION À LA BASE DE DONNÉES ---
$host = 'localhost';
$dbname = 'evasion';
$db_user = 'root';
$db_pass = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$user_id = $_SESSION['user_id'];
$message_succes = "";
$message_erreur = "";

// --- TRAITEMENT DES FORMULAIRES ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Mise à jour des infos (Nom / Email)
    if (isset($_POST['update_info'])) {
        $nom = htmlspecialchars(trim($_POST['nom']));
        $email = htmlspecialchars(trim($_POST['email']));
        
        $stmt = $pdo->prepare("UPDATE utilisateurs SET nom = ?, email = ? WHERE id = ?");
        if ($stmt->execute([$nom, $email, $user_id])) {
            $_SESSION['user_nom'] = $nom; // On met à jour la session
            $message_succes = "Vos informations ont été mises à jour.";
        } else {
            $message_erreur = "Erreur lors de la mise à jour.";
        }
    }

    // 2. Mise à jour du Mot de passe
    if (isset($_POST['update_password'])) {
        $old_pass = $_POST['old_password'];
        $new_pass = $_POST['new_password'];
        
        // On récupère l'ancien mot de passe crypté pour vérifier
        $stmt = $pdo->prepare("SELECT mot_de_passe FROM utilisateurs WHERE id = ?");
        $stmt->execute([$user_id]);
        $user_data = $stmt->fetch();

        if (password_verify($old_pass, $user_data['mot_de_passe'])) {
            $new_pass_crypted = password_hash($new_pass, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE utilisateurs SET mot_de_passe = ? WHERE id = ?");
            $stmt->execute([$new_pass_crypted, $user_id]);
            $message_succes = "Mot de passe modifié avec succès.";
        } else {
            $message_erreur = "L'ancien mot de passe est incorrect.";
        }
    }

    // 3. Mise à jour de la Photo de profil
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
        $dossier_upload = 'uploads/';
        // Si le dossier n'existe pas, on le crée
        if (!is_dir($dossier_upload)) {
            mkdir($dossier_upload, 0777, true);
        }

        $extension = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
        $extensions_autorisees = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($extension, $extensions_autorisees)) {
            // On renomme le fichier avec l'ID du user pour éviter les conflits (ex: 5_avatar.jpg)
            $nouveau_nom = $user_id . '_avatar.' . $extension;
            $chemin_final = $dossier_upload . $nouveau_nom;

			if (move_uploaded_file($_FILES['avatar']['tmp_name'], $chemin_final)) {
                $stmt = $pdo->prepare("UPDATE utilisateurs SET photo_profil = ? WHERE id = ?");
                $stmt->execute([$nouveau_nom, $user_id]);
                
                $_SESSION['user_photo'] = $nouveau_nom; 
                
                $message_succes = "Photo de profil mise à jour.";
            } else {
                $message_erreur = "Erreur lors de l'enregistrement de l'image.";
            }
        } else {
            $message_erreur = "Format d'image non autorisé (JPG, PNG, GIF uniquement).";
        }
    }

    // 4. SUPPRESSION DU COMPTE
    if (isset($_POST['delete_account'])) {
        // (Optionnel mais recommandé) Supprimer la photo de profil du serveur
        $stmt = $pdo->prepare("SELECT photo_profil FROM utilisateurs WHERE id = ?");
        $stmt->execute([$user_id]);
        $user_data = $stmt->fetch();
        
        if ($user_data && !empty($user_data['photo_profil']) && $user_data['photo_profil'] != 'default.png') {
            $chemin_photo = 'uploads/' . $user_data['photo_profil'];
            if (file_exists($chemin_photo)) {
                unlink($chemin_photo); // Supprime le fichier physique
            }
        }

        // Suppression de l'utilisateur dans la base de données
        $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id = ?");
        if ($stmt->execute([$user_id])) {
            // On détruit la session et on renvoie à l'accueil
            $_SESSION = array();
            session_destroy();
            header("Location: index.php");
            exit();
        } else {
            $message_erreur = "Une erreur est survenue lors de la suppression de votre compte.";
        }
    }
}

// --- RÉCUPÉRATION DES DONNÉES DE L'UTILISATEUR POUR L'AFFICHAGE ---
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Définir l'image par défaut si l'utilisateur n'en a pas
$photo_profil = !empty($user['photo_profil']) && $user['photo_profil'] != 'default.png' ? 'uploads/' . $user['photo_profil'] : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - ÉVASION</title>
    <link rel="stylesheet" href="css/style.css?v=2">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="profile-page-bg">

    <nav class="nav-profile">
        <div class="logo"><a href="index.php">ÉVASION</a></div>
        <ul class="nav-links">
            <li><a href="index.php">Accueil</a></li>
            <li><a href="#">Offres</a></li>
            <li class="user-menu-container">
                <a href="#" class="user-icon">
                    <img src="<?php echo $photo_profil; ?>" alt="Avatar" class="nav-avatar">
                </a>
                
                <div class="user-dropdown">
                    <div class="user-name">Bonjour, <?php echo htmlspecialchars($user['nom']); ?></div>
                    <hr>
                    <a href="profil.php"><i class="fas fa-id-badge"></i> Profil</a>
                    <a href="voyages.php"><i class="fas fa-suitcase-rolling"></i> Mes voyages</a>
                    <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Se déconnecter</a>
                </div>
            </li>
        </ul>
    </nav>

    <div class="profile-container">
        
        <?php if (!empty($message_succes)): ?>
            <div class="error-message" style="background: #e8f5e9; color: #2e7d32; text-align:center; margin-bottom: 20px;"><?php echo $message_succes; ?></div>
        <?php endif; ?>
        <?php if (!empty($message_erreur)): ?>
            <div class="error-message" style="text-align:center; margin-bottom: 20px;"><?php echo $message_erreur; ?></div>
        <?php endif; ?>

        <div class="profile-grid">
            
            <div class="profile-sidebar">
                <div class="avatar-section">
                    <img src="<?php echo $photo_profil; ?>" alt="Photo de profil" class="main-avatar">
                    
                    <form action="profil.php" method="POST" enctype="multipart/form-data" class="upload-form">
                        <label for="file-upload" class="custom-file-upload">
                            <i class="fas fa-camera"></i> Changer la photo
                        </label>
                        <input id="file-upload" type="file" name="avatar" accept="image/*" onchange="this.form.submit()">
                    </form>
                </div>

                <div class="sidebar-info">
                    <h3><?php echo htmlspecialchars($user['nom']); ?></h3>
                    <p>Membre depuis le <?php echo date('d/m/Y', strtotime($user['date_inscription'])); ?></p>
                </div>

                <a href="logout.php" class="btn-logout-sidebar"><i class="fas fa-sign-out-alt"></i> Se déconnecter</a>
            </div>

            <div class="profile-content">
                <h2>Informations Personnelles</h2>
                
                <form action="profil.php" method="POST" class="profile-form">
                    <input type="hidden" name="update_info" value="1">
                    
                    <div class="form-group">
                        <label>Nom complet</label>
                        <input type="text" name="nom" value="<?php echo htmlspecialchars($user['nom']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Adresse E-mail</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    <button type="submit" class="btn-blue">Sauvegarder les modifications</button>
                </form>

                <hr class="profile-divider">

                <h2>Sécurité</h2>
                
                <form action="profil.php" method="POST" class="profile-form">
                    <input type="hidden" name="update_password" value="1">
                    
                    <div class="form-group">
                        <label>Ancien mot de passe</label>
                        <input type="password" name="old_password" required placeholder="Nécessaire pour modifier">
                    </div>
                    <div class="form-group">
                        <label>Nouveau mot de passe</label>
                        <input type="password" name="new_password" required placeholder="Votre nouveau mot de passe">
                    </div>
                    <button type="submit" class="btn-blue">Mettre à jour le mot de passe</button>
                </form>

                <hr class="profile-divider">

                <h2 style="color: #d32f2f;">Zone Dangereuse</h2>
                <div style="background: #ffebee; border: 1px solid #ef5350; padding: 20px; border-radius: 15px; margin-top: 15px;">
                    <p style="color: #c62828; font-size: 14px; margin-bottom: 15px;">
                        <strong>Attention :</strong> La suppression de votre compte est définitive. Toutes vos données (y compris l'historique de vos voyages) seront effacées de manière irréversible.
                    </p>
                    <form action="profil.php" method="POST" onsubmit="return confirm('Êtes-vous absolument sûr de vouloir supprimer votre compte ? Cette action est IRRÉVERSIBLE.');">
                        <input type="hidden" name="delete_account" value="1">
                        <button type="submit" class="btn-blue" style="background-color: #e53935; box-shadow: none;">
                            <i class="fas fa-trash-alt"></i> Supprimer mon compte
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>

</body>
</html>