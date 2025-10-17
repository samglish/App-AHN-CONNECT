
<?php
session_start();
ob_start();
require_once 'db.php';
require_once 'header.php';
require_once 'functions.php';

$success = '';
$error = '';

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (!empty($title) && !empty($content)) {
        $stmt = $conn->prepare("INSERT INTO department_news (title, content) VALUES (?, ?)");
        $stmt->bind_param("ss", $title, $content);
        if ($stmt->execute()) {
            $success = "Annonce publiée avec succès !";
            // Redirection vers la page des actualités après la publication
            header("Location:news.php");
            exit();
        } else {
            $error = "Erreur lors de la publication.";
        }
        $stmt->close();
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
ob_end_flush();
?>

<div class="container" style="max-width: 600px; margin: auto; margin-top: 30px;">
    <h2>Nouvelle Annonce</h2>

    <?php if ($success): ?>
        <p style="color: green;"><?= $success ?></p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="title">Titre</label>
        <input type="text" name="title" id="title" required style="width: 100%; padding: 10px; margin-bottom: 10px;">

        <label for="content">Contenu</label>
        <textarea name="content" id="content" rows="5" required style="width: 100%; padding: 10px; margin-bottom: 10px;"></textarea>

        <button type="submit" style="padding: 10px 20px;" class="btn btn-primary">Publier</button>
    </form>
</div>
 <?php require_once 'footer.php'; ?>
