
<?php
session_start();
require_once 'db.php';
require_once 'header.php';
require_once 'functions.php';
if (!isset($_SESSION['id'])) {
    $_SESSION['error'] = "Connectez-vous pour accéder à cette page.";
    header("Location: login.php");
    exit();
}

// Vérifier si c’est un administrateur
$is_admin = (strtolower($_SESSION['role'] ?? '') === 'admin');

$news = get_news($conn);

?>
 <center>
 <div class="sidebar">
 <h4><a href="amis.php" class="btn btn-primary">Amis</a> </br></br></h4>
      
      <?php if ($is_admin): ?>
<center>
   <h4> <a href="actu.php" class="btn btn-primary">Nouvelle Annonce</a></center></h4><br>
<?php endif; ?>

    <div id="resultats">
            <h3 class="section-title">Actualités </h3>  
            <?php foreach ($news as $item): ?>
                <div class="news-card">
                    <div class="news-title"><?= $item['title'] ?></div>
                    <div class="news-date">Publié le <?= date('d/m/Y H:i', strtotime($item['created_at'])) ?></div>
                    <div class="news-content"><?= $item['content'] ?></div>
                </div>
            <?php endforeach; ?>
        </div>
            </div>
                <?php require_once 'footer.php'; ?>

