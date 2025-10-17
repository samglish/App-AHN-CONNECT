<?php
session_start();
require_once 'db.php';
require_once 'header.php';
require_once 'functions.php';

// Vérification connexion
if (!isset($_SESSION['id'])) {
    $_SESSION['error'] = "Connectez-vous pour accéder à cette page.";
    header("Location: login.php");
    exit();
}

// Récupérer l'id de l'utilisateur à afficher (GET ou session)
$user_id = $_GET['id'] ?? $_SESSION['id'];

// Infos utilisateur
$sql = "SELECT * FROM etudiants WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    echo "Utilisateur non trouvé.";
    exit();
}

// Récupérer les posts de l'utilisateur
$posts_sql = "SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC";
$stmt = mysqli_prepare($conn, $posts_sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$posts_result = mysqli_stmt_get_result($stmt);

$posts = [];
while ($row = mysqli_fetch_assoc($posts_result)) {
    // Récupérer les fichiers liés au post
    $files_sql = "SELECT file_name, file_path FROM post_files WHERE post_id = ?";
    $stmt_files = mysqli_prepare($conn, $files_sql);
    mysqli_stmt_bind_param($stmt_files, "i", $row['id']);
    mysqli_stmt_execute($stmt_files);
    $files_result = mysqli_stmt_get_result($stmt_files);

    $files = [];
    while ($f = mysqli_fetch_assoc($files_result)) {
        $files[] = $f;
    }
    $row['files'] = $files;

    $posts[] = $row;
}

$full_name = htmlspecialchars($user['prenom'] . ' ' . $user['nom']);
?>

<div class="profile-container">
    <div class="profile-header">
        <div class="profile-avatar">
        <a href="uploads/<?= htmlspecialchars($user['photo_profil'] ?? 'default.jpg') ?>">
            <img src="uploads/<?= htmlspecialchars($user['photo_profil'] ?? 'default.jpg') ?>" alt="Photo de profil"></a>
        </div>
        <div class="profile-info">
            <h1><?= $full_name ?></h1>
            <p class="profile-level"><?= htmlspecialchars($user['filiere'] ?? 'Étudiant') ?></p>
            <p class="profile-bio"><?= htmlspecialchars($user['bio'] ?? 'Pas de bio pour le moment.') ?></p>

            <?php if ($user_id == $_SESSION['id']): ?>
                <a href="edit_profile.php" class="btn btn-primary">Modifier le profil</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="profile-content">
        <h2>Publications</h2>

           <p class="no-posts">Vous ne pouvez pas voir ses publications car vous n'êtes pas encore amis. Envoyez-lui une demande pour accéder à son contenu.</p>

      
    </div>
</div>
<style>
/* Images dans les posts */
.post-image {
    max-width: 100%;   /* Empêche de dépasser l'écran */
    height: auto;      /* Garde les proportions */
    border-radius: 8px;
    margin-top: 8px;
    display: block;
}

/* Conteneur des fichiers */
.post-files {
    margin-top: 10px;
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.file-download {
    word-break: break-word;
    max-width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>
<?php require_once 'footer.php'; ?>

