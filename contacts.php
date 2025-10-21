<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db.php';
include 'header.php';       // ton header AHN CONNECT
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
<style>
body { font-family: Arial; background: #f3f4f6; }
.container { width: 100%; margin: 40px auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px #ccc; }
.friend { display: flex; align-items: center; justify-content: space-between; padding: 10px; border-bottom: 1px solid #eee; }
.friend img { width: 50px; height: 50px; border-radius: 50%; margin-right: 10px; }
.friend a { text-decoration: none; background: #007bff; color: white; padding: 8px 15px; border-radius: 8px; }
</style>
</head>
    <h2>👥 Mes amis</h2>
    <?php foreach ($amis as $ami): ?>
        <div class="friend">
            <div class="friend-info">
                <img src="<?= htmlspecialchars($ami['photo_profil'] ?? 'default.jpg') ?>" alt="">
                <strong><?= htmlspecialchars($ami['prenom'] . " " . $ami['nom']) ?></strong>
            </div>
            <a href="messages.php?id=<?= $ami['id'] ?>">💬 Discuter</a>
        </div>
    <?php endforeach; ?>


