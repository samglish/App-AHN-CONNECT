<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'db.php'; // connexion à la base

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$mon_id = $_SESSION['id'];

// Vérifie si on a un destinataire sélectionné
$destinataire_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Récupération de la liste des amis (tous les autres étudiants sauf toi)
$amis = [];
$result = $conn->query("SELECT id, nom, prenom, photo_profil FROM etudiants WHERE id != $mon_id");
while ($row = $result->fetch_assoc()) {
    $amis[] = $row;
}

// Si un destinataire est choisi, récupère ses infos
$destinataire = null;
if ($destinataire_id > 0) {
    $stmt = $conn->prepare("SELECT id, nom, prenom, photo_profil FROM etudiants WHERE id = ?");
    $stmt->bind_param("i", $destinataire_id);
    $stmt->execute();
    $destinataire = $stmt->get_result()->fetch_assoc();
}

// Envoi d’un message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $destinataire_id > 0) {
    $message = trim($_POST['message']);
    if ($message !== '') {
    $stmt = $conn->prepare("INSERT INTO messages (expediteur_id, destinataire_id, contenu) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $mon_id, $destinataire_id, $message);
        $stmt->execute();
    }
}

// Récupération des messages entre moi et le destinataire
$messages = [];
if ($destinataire_id > 0) {
$stmt = $conn->prepare("
    SELECT m.*, e.prenom, e.nom
    FROM messages m
    JOIN etudiants e ON m.expediteur_id = e.id
    WHERE (m.expediteur_id = ? AND m.destinataire_id = ?)
       OR (m.expediteur_id = ? AND m.destinataire_id = ?)
    ORDER BY m.date_envoi ASC
");

    $stmt->bind_param("iiii", $mon_id, $destinataire_id, $destinataire_id, $mon_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Messagerie privée - AHN CONNECT</title>
<style>
body { font-family: Arial; display: flex; }
.sidebar { width: 25%; background: #f1f1f1; padding: 10px; height: 100vh; overflow-y: auto; }
.chat { flex: 1; display: flex; flex-direction: column; }
.messages { flex: 1; padding: 10px; overflow-y: auto; background: #fafafa; }
.message { margin-bottom: 8px; }
.message.me { text-align: right; }
form { display: flex; padding: 10px; background: #ddd; }
input[type=text] { flex: 1; padding: 8px; }
button { padding: 8px 12px; }
.friend { margin-bottom: 10px; }
.friend a { text-decoration: none; color: #333; }
.friend img { width: 40px; height: 40px; border-radius: 50%; vertical-align: middle; }
</style>
</head>
<body>

<div class="sidebar">
    <h3>Mes amis</h3>
    <?php foreach ($amis as $a): ?>
        <div class="friend">
            <a href="messages.php?id=<?= $a['id'] ?>">
                <img src="<?= $a['photo_profil'] ?>" alt="photo">
                <?= htmlspecialchars($a['prenom'] . ' ' . $a['nom']) ?>
            </a>
        </div>
    <?php endforeach; ?>
</div>

<div class="chat">
    <?php if ($destinataire): ?>
        <h3>Discussion avec <?= htmlspecialchars($destinataire['prenom'] . ' ' . $destinataire['nom']) ?></h3>
        <div class="messages">
            <?php foreach ($messages as $m): ?>
                <div class="message <?= ($m['expediteur_id'] == $mon_id) ? 'me' : '' ?>">
                    <strong><?= htmlspecialchars($m['prenom']) ?>:</strong>
                    <?= htmlspecialchars($m['contenu']) ?><br>
                    <small><?= $m['date_envoi'] ?></small>
                </div>
            <?php endforeach; ?>
        </div>
        <form method="POST">
            <input type="text" name="message" placeholder="Écrire un message..." autocomplete="off">
            <button type="submit">Envoyer</button>
        </form>
    <?php else: ?>
        <p>Sélectionnez un ami pour commencer une discussion.</p>
    <?php endif; ?>
</div>

</body>
</html>

