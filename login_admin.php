
<?php
session_start();
ob_start();
require_once 'db.php';
require_once 'header.php';
require_once 'functions.php';

if ($_SESSION['admin']) {
    header("Location: actu.php");
    exit();
}
ob_end_flush();
?>
 <center>
    <br>
<h2 >Administrateur</h2>
 <div id="formulaire">

    <form action="login_admin_trait.php" method="post">
       
         <input type="text" name="username" placeholder="nom d'utilisateur" required><br>
         <input type="password" name="password" placeholder="Mot de passe" required><br>
        <input type="submit" value="Se connecter" class="btn btn-primary">
       <p>Problème technique? <a href="mailto:beidisamuel11@gmail.com">contactez nous</a></p>
</center>
</div>
    </form>
<?php require_once 'footer.php'; ?>

