<?php
session_start();

$erreur = "";
$succes = "";

// --- 1. CONNEXION À LA BASE DE DONNÉES MYSQL ---
$host = 'localhost';
$dbname = 'evasion';
$db_user = 'root'; // Par défaut sur XAMPP
$db_pass = '';     // Par défaut sur XAMPP, il n'y a pas de mot de passe

try {
    // On se connecte à MySQL avec PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// --- 2. TRAITEMENT DU FORMULAIRE ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // On récupère et nettoie les données
    $nom = htmlspecialchars(trim($_POST['nom']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Vérifications de base
    if ($password !== $confirm_password) {
        $erreur = "Les mots de passe ne correspondent pas.";
    } else {
        // On vérifie si l'email existe déjà dans la base
        $checkEmail = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
        $checkEmail->execute([$email]);
        
        if ($checkEmail->rowCount() > 0) {
            $erreur = "Cette adresse e-mail est déjà utilisée.";
        } else {
            // SÉCURITÉ : On crypte le mot de passe avant de l'enregistrer !
            $mot_de_passe_crypte = password_hash($password, PASSWORD_DEFAULT);

            // On insère le nouvel utilisateur
            $insertUser = $pdo->prepare("INSERT INTO utilisateurs (nom, email, mot_de_passe) VALUES (?, ?, ?)");
            
            if ($insertUser->execute([$nom, $email, $mot_de_passe_crypte])) {
                $succes = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
            } else {
                $erreur = "Une erreur est survenue lors de l'inscription.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S'inscrire - ÉVASION</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body class="login-page">

    <div class="login-wrapper">
        <div class="login-card">
            <div class="logo-login">ÉVASION</div>
            <h2>Créer un compte</h2>
            <p>Rejoignez-nous pour organiser votre prochain voyage.</p>

            <?php if (!empty($erreur)): ?>
                <div class="error-message"><?php echo $erreur; ?></div>
            <?php endif; ?>
            
            <?php if (!empty($succes)): ?>
                <div class="error-message" style="background: #e8f5e9; color: #2e7d32;"><?php echo $succes; ?></div>
            <?php endif; ?>

            <form action="register.php" method="POST">
                <div class="form-group">
                    <label for="nom">Nom complet</label>
                    <input type="text" id="nom" name="nom" required placeholder="Jean Dupont">
                </div>

                <div class="form-group">
                    <label for="email">Adresse E-mail</label>
                    <input type="email" id="email" name="email" required placeholder="exemple@email.com">
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required placeholder="Créer un mot de passe">
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirmer le mot de passe</label>
                    <input type="password" id="confirm_password" name="confirm_password" required placeholder="Répéter le mot de passe">
                </div>

                <button type="submit" class="btn-blue full-width btn-login-submit">S'inscrire</button>
            </form>
            
            <div class="register-link">
                Vous avez déjà un compte ? <a href="login.php">Se connecter</a>
            </div>

            <div class="login-footer">
                <a href="index.php">← Retour à l'accueil</a>
            </div>
        </div>
    </div>

</body>
</html>