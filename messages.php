<?php
session_start();
include 'db.php';
include 'header.php';       // ton header AHN CONNECT
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['id'];

// Liste des amis
$amis_query = $conn->prepare("SELECT id, nom, prenom, photo_profil FROM etudiants WHERE id != ?");
$amis_query->bind_param("i", $id_user);
$amis_query->execute();
$amis_result = $amis_query->get_result();

// Envoi d’un message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['destinataire_id'], $_POST['contenu'])) {
    $destinataire_id = $_POST['destinataire_id'];
    $contenu = trim($_POST['contenu']);
    if ($contenu !== "") {
        $insert = $conn->prepare("INSERT INTO messages (expediteur_id, destinataire_id, contenu, date_envoi) VALUES (?, ?, ?, NOW())");
        $insert->bind_param("iis", $id_user, $destinataire_id, $contenu);
        $insert->execute();
    }
}

// Chargement des messages
$messages = [];
$ami_info = null;
if (isset($_GET['ami'])) {
    $ami_id = $_GET['ami'];

    // Récup info ami
    $ami_stmt = $conn->prepare("SELECT nom, prenom, photo_profil FROM etudiants WHERE id = ?");
    $ami_stmt->bind_param("i", $ami_id);
    $ami_stmt->execute();
    $ami_info = $ami_stmt->get_result()->fetch_assoc();

    // Messages échangés
    
    
    $stmt = $conn->prepare("
        SELECT m.*, e.nom, e.prenom, e.photo_profil
        FROM messages m
        JOIN etudiants e ON m.expediteur_id = e.id
        WHERE (m.expediteur_id = ? AND m.destinataire_id = ?)
           OR (m.expediteur_id = ? AND m.destinataire_id = ?)
        ORDER BY m.date_envoi ASC
    ");
    $stmt->bind_param("iiii", $id_user, $ami_id, $ami_id, $id_user);
    $stmt->execute();
    $messages = $stmt->get_result();
}
?>

<style>
/* === LAYOUT GLOBAL === */

.main-container {
    display: flex;
    height: calc(100vh - 120px);
    overflow: hidden;
}

/* === ZONE AMIS === */
.friends-list {
    width: 15%;
    min-width: 70px;
    background: #fff;
    border-right: 1px solid #ddd;
    display: flex;
    flex-direction: column;
    align-items: center;
    overflow-y: auto;
}
.friend {
    text-align: center;
    padding: 10px 5px;
}
.friend a {
    text-decoration: none;
    color: #333;
    font-size: 12px;
}
.friend img {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 5px;
    border: 2px solid #007bff;
}

/* === ZONE CHAT === */
.chat-area {
    width: 85%;
    display: flex;
    flex-direction: column;
    background: #e9f0fa;
}

.chat-header {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #3b7ca7;

    color: white;
    padding: 10px 15px;
}
.chat-header img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #fff;
}

/* === MESSAGES === */
.messages-box {
    flex: 1;
    overflow-y: auto;
    padding: 15px;
    display: flex;
    flex-direction: column;
    scroll-behavior: smooth;
}
.message {
    max-width: 70%;
    margin-bottom: 12px;
    padding: 10px 14px;
    border-radius: 15px;
    line-height: 1.4;
    word-wrap: break-word;
    position: relative;
}
.message.sent {
    align-self: flex-end;
    background-color: #3b7ca7;
    color: white;
}
.message.received {
    align-self: flex-start;
    background-color: #fff;
    border: 1px solid #ccc;
}
.message .info {
    font-size: 11px;
    opacity: 0.8;
    margin-bottom: 2px;
}
.message .time {
    font-size: 10px;
    color: rgba(255, 255, 255, 0.8);
    position: absolute;
    bottom: -13px;
    right: 10px;
}
.message.received .time {
    color: #777;
}

/* === FORMULAIRE === */
.send-box {
    display: flex;
    align-items: center;
    padding: 10px;
    background: #fff;
    border-top: 1px solid #ddd;
}
.send-box input[type="text"] {
    flex: 1;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 20px;
    outline: none;
}
.send-box button {
    background: none;
    border: none;
    color: #3b7ca7;
    font-size: 22px;
    margin-left: 10px;
    cursor: pointer;
    transition: transform 0.2s;
}
.send-box button:hover {
    transform: scale(1.1);
}

/* === RESPONSIVE === */
@media (max-width: 768px) {
    .friends-list {
        width: 15%;
        min-width: 55px;
    }
    .chat-area {
        width: 85%;
    }
}
</style>

<div class="main-container">

    <!-- Liste des amis -->
    <div class="friends-list">
    <div class="friend">
    <a href="chat.php" title="Discussions" style="text-decoration:none; color:#333;">
    <img src="uploads/logogp.jpeg" alt=""> GROUPE</a></br>
    <a href="contacts.php" title="Contacts" style="text-decoration:none; color:#333;">
    <img src="uploads/logoct.jpeg" alt="">CONTACTS</a>
    </div>
        <?php while ($ami = $amis_result->fetch_assoc()): ?>
            <div class="friend">
                <a href="?ami=<?= $ami['id'] ?>">
                    <img src="uploads/<?= htmlspecialchars($ami['photo_profil']) ?>" alt="Photo">
                </a>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Zone de chat -->
    <div class="chat-area">
        <?php if ($ami_info): ?>
            <div class="chat-header">
                <img src="uploads/<?= htmlspecialchars($ami_info['photo_profil']) ?>" alt="">
                <strong><?= htmlspecialchars($ami_info['prenom'] . ' ' . $ami_info['nom']) ?></strong>
            </div>
        <?php endif; ?>

        <div class="messages-box" id="messages">
            <?php if (isset($_GET['ami'])): ?>
                <?php while ($msg = $messages->fetch_assoc()): ?>
                    <div class="message <?= ($msg['expediteur_id'] == $id_user) ? 'sent' : 'received' ?>">
                        <div class="info">
                            <?= htmlspecialchars($msg['prenom'] . ' ' . $msg['nom']) ?>
                        </div>
                        <?= htmlspecialchars($msg['contenu']) ?>
                        <div class="time">
                            <?= date("H:i", strtotime($msg['date_envoi'])) ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align:center;color:#777;">Sélectionnez un ami pour commencer à discuter</p>
            <?php endif; ?>
        </div>

        <?php if (isset($_GET['ami'])): ?>
            <form method="POST" class="send-box">
                <input type="hidden" name="destinataire_id" value="<?= $_GET['ami'] ?>">
                <input type="text" name="contenu" placeholder="Écrire un message..." required>
                <button type="submit"><i class="fas fa-paper-plane"></i></button>
            </form>
        <?php endif; ?>
    </div>
</div>

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

