<?php
// On démarre la session pour garder l'utilisateur connecté
session_start();

$erreur = ""; // Variable pour stocker les messages d'erreur

// Si le formulaire a été soumis (clic sur le bouton)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // On récupère et nettoie les données
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];

    // --- 1. CONNEXION À LA BASE DE DONNÉES MYSQL ---
    $host = 'localhost';
    $dbname = 'evasion';
    $db_user = 'root'; // Par défaut sur XAMPP
    $db_pass = '';     // Par défaut sur XAMPP

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $db_user, $db_pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // --- 2. VÉRIFICATION DES IDENTIFIANTS ---
        // On cherche si un utilisateur possède cet email
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si l'utilisateur existe ET que le mot de passe correspond au mot de passe crypté
        if ($user && password_verify($password, $user['mot_de_passe'])) {
            
            // Succès ! On stocke ses infos dans la session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nom'] = $user['nom'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_photo'] = $user['photo_profil'];
            // Redirection vers l'accueil
            header("Location: index.php");
            exit();
            
        } else {
            // Échec : Mauvais email ou mauvais mot de passe
            $erreur = "Adresse e-mail ou mot de passe incorrect.";
        }

    } catch(PDOException $e) {
        $erreur = "Erreur de connexion à la base de données.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - ÉVASION</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body class="login-page">

    <div class="login-wrapper">
        <div class="login-card">
            <div class="logo-login">ÉVASION</div>
            <h2>Bon retour parmi nous</h2>
            <p>Connectez-vous pour gérer vos réservations.</p>

            <?php if (!empty($erreur)): ?>
                <div class="error-message"><?php echo $erreur; ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="form-group">
                    <label for="email">Adresse E-mail</label>
                    <input type="email" id="email" name="email" required placeholder="exemple@email.com">
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required placeholder="Votre mot de passe">
                </div>

                <button type="submit" class="btn-blue full-width btn-login-submit">Se connecter</button>
            </form>
            
            <div class="register-link">
                Vous n'avez pas encore de compte ? <a href="register.php">S'inscrire</a>
            </div>

            <div class="login-footer">
                <a href="index.php">← Retour à l'accueil</a>
            </div>
        </div>
    </div>

</body>
</html>