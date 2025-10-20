<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'db.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['id'];

// Récupérer tous les autres étudiants sauf soi-même
$stmt = $conn->prepare("SELECT id, nom, prenom, photo_profil FROM etudiants WHERE id != ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$amis = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Mes amis | AHN CONNECT</title>
<link rel="stylesheet" href="style.css">
<style>
body { font-family: Arial; background: #f3f4f6; }
.container { width: 60%; margin: 40px auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px #ccc; }
.friend { display: flex; align-items: center; justify-content: space-between; padding: 10px; border-bottom: 1px solid #eee; }
.friend img { width: 50px; height: 50px; border-radius: 50%; margin-right: 10px; }
.friend-info { display: flex; align-items: center; }
.friend a { text-decoration: none; background: #007bff; color: white; padding: 8px 15px; border-radius: 8px; }
</style>
</head>
<body>

<div class="container">
    <h2>👥 Mes amis</h2>
    <?php foreach ($amis as $ami): ?>
        <div class="friend">
            <div class="friend-info">
                <img src="<?= htmlspecialchars($ami['photo'] ?? 'default.jpg') ?>" alt="">
                <strong><?= htmlspecialchars($ami['prenom'] . " " . $ami['nom']) ?></strong>
            </div>
            <a href="messages.php?id=<?= $ami['id'] ?>">💬 Discuter</a>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>

