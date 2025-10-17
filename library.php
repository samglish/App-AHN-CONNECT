<?php
session_start();
require_once "db.php";
require_once 'header.php';
require_once 'functions.php';


// Vérifier si l’utilisateur est connecté
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Vérifier si c’est un administrateur
$is_admin = (strtolower($_SESSION['role'] ?? '') === 'admin');
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
  width: 100px;
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
<h2><center>Bibliothèque</center></h2><br>
<?php if ($is_admin): ?>
<center>
   <h4> <a href="upload_sujet.php" class="btn btn-primary">Ajouter un sujet</a></center></h4><br>
<?php endif; ?>

<ul>
    <div id="Accueil" class="year-folder">
     <a href="ihn1.php" target="_blank"><img src ="uploads/poly.jpg" width="40%"/>IHN 1</a>
     <a href="ihn2.php" target="_blank"><img src ="uploads/poly.jpg" width="40%"/>IHN 2</a>
    <a href="ihn3.php" target="_blank"><img src ="uploads/poly.jpg" width="40%"/>IHN 3</a>
     <a href="ihn4.php" target="_blank"><img src ="uploads/poly.jpg" width="40%"/>IHN 4</a>
      <a href="ian1.php" target="_blank"><img src ="uploads/poly.jpg" width="40%"/>IAN 1</a>
     <a href="ian2.php" target="_blank"><img src ="uploads/poly.jpg" width="40%"/>IAN 2</a>
    <a href="ian3.php" target="_blank"><img src ="uploads/poly.jpg" width="40%"/>IAN 3</a>
     <a href="ian4.php" target="_blank"><img src ="uploads/poly.jpg" width="40%"/>IAN 4</a>
      <a href="autres.php" target="_blank"><img src ="uploads/autres.png" width="40%"/></a>
</ul>
</div>
</div>
</div>
<?php require_once 'footer.php'; ?>
