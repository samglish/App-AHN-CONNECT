<?php
session_start();
require_once "db.php";
require_once 'header.php';
require_once 'functions.php';

$sql = "SELECT * FROM bibliotheque WHERE classe like 'ihn4' or classe like 'IHN4'  ORDER BY date_ajout DESC";
$result = $conn->query($sql);

// Vérifier si l’utilisateur est connecté
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Vérifier si c’est un administrateur
$is_admin = ($_SESSION['role'] ?? '') === 'admin';
?>

    <style>



.year-folder {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 2.5rem;
}

.year-folder a {
  display: flex;
  flex-direction: column;
  align-items: center;
  border-radius: 5px;
  box-shadow: 0 2px 8px rgba(195, 238, 229, 0.93);
  transition: transform 0.2s;
  width: 100px;
  height:auto;
}

.year-folder a:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.year-folder img {
  width: 65px;
  margin-bottom: 0.5rem;
}

.year-folder a span,
.year-folder a {
  color: #333;
  font-weight: 500;
  font-size: 0.9rem;
}

/* Responsive */
@media (max-width: 600px) {
  .year-folder a {
    .year-folder a {
    display: flex;
  flex-direction: column;
  align-items: center;
  text-decoration: none;
  background-color: #fff;
  padding: 1rem;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  transition: transform 0.2s;
  width: 80px;
  }

  .year-folder img {
    width: 90px;
  }

  #Accueil h1 {
    font-size: 2rem;
  }
}
    </style>




  <div class="sidebar">
 <div id="formulaire">
<h2><center>IHN 4</center></h2><br>


<ul>
<?php while ($doc = $result->fetch_assoc()): ?>
    <div id="Accueil" class="year-folder">
        <a href="uploads/<?= htmlspecialchars($doc['fichier']) ?>" target="_blank"><img src ="uploads/pdficon.png" width="40%"/><?= htmlspecialchars($doc['titre']) ?></a>
    
<?php endwhile; ?>
</ul>
</div>
</div>
</div>
<?php require_once 'footer.php'; ?>
