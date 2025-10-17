<?php
session_start();
ob_start();
require_once "db.php"; // ta connexion mysqli
require_once 'header.php';
require_once 'functions.php';

// Vérifier si admin (à adapter selon ton système de rôle)
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    die("Accès refusé.");
}

if (isset($_POST['submit'])) {
    $titre = $_POST['titre'];
     $classe = $_POST['classe'];
    $file = $_FILES['fichier'];

    // Vérifier extension
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $allowed = ['pdf','doc','docx'];
    if (!in_array(strtolower($ext), $allowed)) {
        die("Format non supporté !");
    }

    $filename = time() . "_" . basename($file['name']);
    $destination = "uploads/" . $filename;

    if (move_uploaded_file($file['tmp_name'], $destination)) {
        $sql = "INSERT INTO bibliotheque (titre,classe, fichier, admin_id) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $titre,$classe, $filename, $_SESSION['id']);
        $stmt->execute();
        $stmt->close();
        header("Location: library.php");
        echo "Sujet ajouté avec succès !";
        
        exit();
    } else {
        echo "Erreur lors de l’upload.";
    }
}
ob_end_flush();
?>
 <div class="sidebar">
     <div id="formulaire">
<center><h2>Ajouter un Sujet</h2>
<form method="post" enctype="multipart/form-data">
   
    <input type="text" name="titre" placeholder="Titre du sujet" required><br>
      <input type="text" name="classe" placeholder="ex classe : IHN1" required><br>
    <input type="file" name="fichier" placeholder="Fichier" required><br>
    <input type="submit" name="submit" value="Envoyer">
</form>
</div>
</div>
</center>
<?php require_once 'footer.php'; ?>
