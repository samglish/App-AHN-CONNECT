<?php
// Affiche les erreurs PHP pour faciliter le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connexion à la base de données
$conn = new mysqli("localhost", "ahnens9421_sam", "Samglish12", "ahnens9421_enspm");
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Vérifie que le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Préparation de la requête
    $sql = "SELECT * FROM admin WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Erreur de préparation : " . $conn->error);
    }

    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Vérification du résultat
    if ($result->num_rows > 0) {
        session_start();
        $_SESSION['admin'] = $username;
        header("Location: actu.php");
        exit();
    } else {
        echo "<script>alert('Nom d’utilisateur ou mot de passe incorrect.'); window.location.href='login_admin.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>