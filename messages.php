<?php
session_start();
ob_start();
include 'db.php';
include 'header.php'; // ton header AHN CONNECT

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['id'];
ob_end_flush();

/* ===============================
   1️⃣ Charger la liste des amis
   =============================== */

// 🟢 On récupère aussi le nombre de messages non lus pour chaque ami
$amis_query = $conn->prepare("
    SELECT e.id, e.nom, e.prenom, e.photo_profil, 
           COUNT(m.id) AS non_lus
    FROM etudiants e
    LEFT JOIN messages m 
      ON m.expediteur_id = e.id 
     AND m.destinataire_id = ? 
     AND m.lu = 0
    WHERE e.id != ?
    GROUP BY e.id, e.nom, e.prenom, e.photo_profil
    ORDER BY non_lus DESC, e.prenom ASC
");
$amis_query->bind_param("ii", $id_user, $id_user);
$amis_query->execute();
$amis_result = $amis_query->get_result();

/* ===============================
   2️⃣ Envoi d’un message
   =============================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['destinataire_id'], $_POST['contenu'])) {
    $destinataire_id = $_POST['destinataire_id'];
    $contenu = trim($_POST['contenu']);
    if ($contenu !== "") {
        $insert = $conn->prepare("INSERT INTO messages (expediteur_id, destinataire_id, contenu, date_envoi, lu) VALUES (?, ?, ?, NOW(), 0)");
        $insert->bind_param("iis", $id_user, $destinataire_id, $contenu);
        $insert->execute();
    }
}

/* ===============================
   3️⃣ Chargement des messages
   =============================== */
$messages = [];
$ami_info = null;

if (isset($_GET['ami'])) {
    $ami_id = $_GET['ami'];

    // Infos de l'ami
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

    // ✅ Marquer les messages reçus de cet ami comme "lus"
    $update = $conn->prepare("
        UPDATE messages 
        SET lu = 1 
        WHERE expediteur_id = ? 
          AND destinataire_id = ? 
          AND lu = 0
    ");
    $update->bind_param("ii", $ami_id, $id_user);
    $update->execute();
}

/* ===============================
   4️⃣ Vérification Ajax des messages non lus
   =============================== */
if (isset($_GET['action']) && $_GET['action'] === 'check_new_messages') {
    $stmt = $conn->prepare("SELECT COUNT(*) AS unread_count FROM messages WHERE destinataire_id = ? AND lu = 0");
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    echo json_encode($result);
    exit;
}
?>

<!-- ===============================
     HTML / INTERFACE
================================== -->

<style>
#message-badge {
  position: absolute;
  top: -6px;
  right: -10px;
  background: red;
  color: white;
  font-size: 11px;
  border-radius: 50%;
  padding: 3px 6px;
  display: none;
}
</style>

<script>
let lastUnreadCount = 0;

function checkNewMessages() {
  fetch('messages.php?action=check_new_messages')
    .then(res => res.json())
    .then(data => {
      const unread = parseInt(data.unread_count) || 0;
      const badge = document.getElementById('message-badge');

      if (badge) {
        badge.textContent = unread > 0 ? unread : '';
        badge.style.display = unread > 0 ? 'inline-block' : 'none';
      }

      if (unread > lastUnreadCount && lastUnreadCount !== 0) {
        const sound = new Audio('notif.mp3');
        sound.play().catch(() => {});
      }

      lastUnreadCount = unread;
    })
    .catch(console.error);
}

setInterval(checkNewMessages, 5000);
checkNewMessages();
</script>

<style>
.main-container {
   display: flex;
    flex-direction: row;
    height: 100dvh; /* ✅ prend toute la hauteur visible, compatible mobile */
    overflow: hidden;
}
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
    position: relative;
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
.friend .unread-badge {
    position: absolute;
    top: 6px;
    right: 12px;
    background: red;
    color: white;
    font-size: 10px;
    border-radius: 50%;
    padding: 2px 5px;
}
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
    position: absolute;
    bottom: -13px;
    right: 10px;
    color: rgba(255, 255, 255, 0.8);
}
.message.received .time {
    color: #777;
}
.send-box {
    display: flex;
    align-items: center;
    padding: 10px;
    background: #fff;
    border-top: 1px solid #ddd;
    position: sticky;
    bottom: 0;
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
@media (max-width: 768px) {
    .friends-list { width: 20%; min-width: 55px; }
    .chat-area { width: 80%; }
}
</style>

<div class="main-container">

    <div class="friends-list">
        <div class="friend">
            <a href="chat.php"><img src="uploads/logogp.jpeg" alt=""> GROUPE</a><br>
            <a href="contacts.php"><img src="uploads/logoct.jpeg" alt=""> CONTACTS</a>
        </div>

        <?php while ($ami = $amis_result->fetch_assoc()): ?>
            <div class="friend">
                <a href="?ami=<?= $ami['id'] ?>">
                    <img src="uploads/<?= htmlspecialchars($ami['photo_profil']) ?>" alt="Photo"><?php echo $ami['nom']  ?>
                    <?php if ($ami['non_lus'] > 0): ?>
                        <span class="unread-badge"><?= $ami['non_lus'] ?></span>
                    <?php endif; ?>
                </a>
            </div>
        <?php endwhile; ?>
    </div>

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
                        <div class="info"><?= htmlspecialchars($msg['prenom'] . ' ' . $msg['nom']) ?></div>
                        <?= htmlspecialchars($msg['contenu']) ?>
                        <div class="time"><?= date("H:i", strtotime($msg['date_envoi'])) ?></div>
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

<script>
// ✅ Scroll automatique vers le dernier message
document.addEventListener("DOMContentLoaded", function() {
    const messagesBox = document.getElementById("messages");
    if (messagesBox) {
        setTimeout(() => {
            messagesBox.scrollTop = messagesBox.scrollHeight;
        }, 400);
    }
});
</script>

