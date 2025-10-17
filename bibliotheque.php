<?php
session_start();
require_once "db.php";
require_once 'header.php';
require_once 'functions.php';

$sql = "SELECT * FROM bibliotheque ORDER BY date_ajout DESC";
$result = $conn->query($sql);

// Vérifier si l’utilisateur est connecté
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Vérifier si c’est un administrateur
$is_admin = ($_SESSION['role'] ?? '') === 'admin';
?>
  <div class="sidebar">
 <div id="formulaire">
<h2><center>Bibliothèque</center></h2><br>
<?php if ($is_admin): ?>
<center>
   <h4> <a href="upload_sujet.php" class="btn btn-primary">Ajouter un sujet</a></center></h4><br>
<?php endif; ?>

<ul>
<?php while ($doc = $result->fetch_assoc()): ?>
    <li>
        <strong><?= htmlspecialchars($doc['titre']) ?></strong>
        (<?= $doc['date_ajout'] ?>)
        👉 <a href="uploads/<?= htmlspecialchars($doc['fichier']) ?>" target="_blank">Télécharger</a>
    </li>
<?php endwhile; ?>
</ul>
</div>
</div>
<?php require_once 'footer.php'; ?>
