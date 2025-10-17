<?php
session_start();
require_once 'db.php';
require_once 'header.php';


if (!isset($_SESSION['id'])) {
    $_SESSION['error'] = "Connectez-vous pour accéder à cette page..";
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id'];

// Connexion déjà dans db.php via $conn

// Traitement du formulaire POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération sécurisée des données
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $filiere = htmlspecialchars($_POST['filiere']);
    $bio = htmlspecialchars($_POST['bio']);

    // Gestion upload photo
    $photo_profil = $_SESSION['profile_pic'] ?? 'default.jpg';

    if (isset($_FILES['photo_profil']) && $_FILES['photo_profil']['error'] == 0) {
        $dossier_upload = 'uploads/';
        $fichier_tmp = $_FILES['photo_profil']['tmp_name'];
        $fichier_nom = time() . '_' . basename($_FILES['photo_profil']['name']);
        $chemin_complet = $dossier_upload . $fichier_nom;

        $type_mime = mime_content_type($fichier_tmp);
        if (strpos($type_mime, 'image') !== false) {
            if (move_uploaded_file($fichier_tmp, $chemin_complet)) {
                $photo_profil = $fichier_nom;
            }
        }
    }

    // Mise à jour en base
    $sql = "UPDATE etudiants SET nom = ?, prenom = ?, filiere = ?, bio = ?, photo_profil = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $nom, $prenom, $filiere, $bio, $photo_profil, $user_id);

    if ($stmt->execute()) {
        // Mise à jour de la session pour la photo si modifiée
        $_SESSION['prenom'] = $prenom;
        $_SESSION['nom'] = $nom;
        $_SESSION['filiere'] = $filiere;
        $_SESSION['profile_pic'] = $photo_profil;

        echo "<script>alert('Profil mis à jour avec succès !'); window.location.href='profile.php';</script>";
        exit();
    } else {
        echo "Erreur lors de la mise à jour : " . $stmt->error;
    }
}

// Requête pour récupérer les données actuelles
$sql = "SELECT nom, prenom, filiere, bio, photo_profil FROM etudiants WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

?>

<h2>Modifier mon profil</h2>
<div id="formulaire">
<form method="post" enctype="multipart/form-data">
    <label>Nom :</label><br>
    <input type="text" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required><br><br>

    <label>Prénom :</label><br>
    <input type="text" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required><br><br>

    <label>Filière :</label><br>
    <input type="text" name="filiere" value="<?= htmlspecialchars($user['filiere']) ?>" required><br><br>

    <label>Bio :</label><br>
    <textarea name="bio" rows="4"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea><br><br>

    <label>Photo de profil actuelle :</label><br>
    <img src="uploads/<?= htmlspecialchars($user['photo_profil'] ?? 'default.jpg') ?>" alt="Photo de profil" style="max-width:150px;"><br><br>

    <label>Changer la photo :</label><br>
    <input type="file" name="photo_profil" accept="image/*"><br><br>

    <button type="submit" class="btn btn-primary">Mettre à jour</button>
</div>
</form>

<?php require_once 'footer.php'; ?>
