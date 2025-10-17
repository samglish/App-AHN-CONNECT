<?php
require_once 'db.php';
require_once 'header.php';
require_once 'functions.php';
?>        
<center>
    <br>
    <h2>Inscription</h2>
    <div id="formulaire">
        <form action="register_trait.php" method="post" enctype="multipart/form-data">
            <input type="text" name="nom" placeholder="Nom" required></br> 
            <input type="text" name="prenom" placeholder="Prénom" required></br>
            <input type="text" name="matricule" placeholder="Matricule" required></br>
            <input type="text" name="numero" placeholder="Numéro" required></br>
            <input type="text" name="filiere" placeholder="Filière" required></br>
            <input type="email" name="email" placeholder="Adresse email" required></br>
            <input type="password" name="mot_de_passe" placeholder="Mot de passe" required><br>
            
            <!-- Upload photo -->
            <label>Photo de profil :</label><br>
            <input type="file" name="photo_profil" accept="image/*"><br><br>

            <input type="submit" value="S'inscrire" class="btn btn-primary">
            <p>Problème technique? <a href="mailto:beidisamuel11@gmail.com">contactez nous</a></p>
        </form>
    </div>
</center>
<?php require_once 'footer.php'; ?>
