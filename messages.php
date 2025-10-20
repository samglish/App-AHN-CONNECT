<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'db.php'; // connexion
include 'header.php'; // ton header AHN CONNECT

if (!isset($_SESSION['id'])) {
    die("Vous devez être connecté !");
}

$mon_id = $_SESSION['id'];
$destinataire_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Liste des amis
$amis = [];
$res = $conn->query("SELECT id, nom, prenom, photo_profil FROM etudiants WHERE id != $mon_id");
while ($row = $res->fetch_assoc()) { $amis[] = $row; }

// Infos destinataire
$destinataire = null;
if ($destinataire_id > 0) {
    $stmt = $conn->prepare("SELECT id, nom, prenom, photo_profil FROM etudiants WHERE id = ?");
    $stmt->bind_param("i", $destinataire_id);
    $stmt->execute();
    $destinataire = $stmt->get_result()->fetch_assoc();
}
?>

<div class="container" style="display:flex; min-height:80vh; padding:20px;">
    <!-- Sidebar amis -->
    <div class="sidebar" style="width:25%; background:#f1f1f1; padding:10px; overflow-y:auto;">
        <h3>Mes amis</h3>
        <?php foreach ($amis as $a): ?>
            <div style="display:flex; align-items:center; margin-bottom:10px; gap:10px;">
                <img src="<?= htmlspecialchars($a['photo_profil']) ?>" style="width:40px; height:40px; border-radius:50%; object-fit:cover;" alt="">
                <a href="messages.php?id=<?= $a['id'] ?>">
                    <?= htmlspecialchars($a['prenom'].' '.$a['nom']) ?>
                </a>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Chat -->
    <div class="chat" style="flex:1; display:flex; flex-direction:column; background:#e9ecef; margin-left:20px; border-radius:8px; overflow:hidden;">
        <?php if($destinataire): ?>
            <h3 style="padding:10px; background:#007bff; color:white; margin:0;"><?= htmlspecialchars($destinataire['prenom'].' '.$destinataire['nom']) ?></h3>
            <div id="messages" style="flex:1; overflow-y:auto; padding:10px;"></div>
            <form id="sendForm" style="display:flex; padding:10px; background:#ddd;">
                <input type="text" id="message" placeholder="Écrire un message..." style="flex:1; padding:10px; border-radius:12px; border:1px solid #ccc;">
                <input type="hidden" id="dest_id" value="<?= $destinataire_id ?>">
                <button type="submit" style="padding:10px 15px; border:none; border-radius:12px; background:#007bff; color:white; cursor:pointer; margin-left:5px;">Envoyer</button>
            </form>
        <?php else: ?>
            <p style="padding:10px;">Sélectionnez un ami pour discuter.</p>
        <?php endif; ?>
    </div>
</div>

<script>
function loadMessages(){
    let dest_id = document.getElementById('dest_id').value;
    fetch('fetch_messages.php?id='+dest_id)
        .then(res=>res.text())
        .then(data=>{
            document.getElementById('messages').innerHTML = data;
            document.getElementById('messages').scrollTop = document.getElementById('messages').scrollHeight;
        });
}

document.getElementById('sendForm')?.addEventListener('submit', function(e){
    e.preventDefault();
    let msg = document.getElementById('message').value.trim();
    let dest_id = document.getElementById('dest_id').value;
    if(msg==='') return;
    let formData = new FormData();
    formData.append('contenu', msg);
    formData.append('recepteur_id', dest_id);
    fetch('send_message.php', {method:'POST', body: formData})
        .then(()=>{ document.getElementById('message').value=''; loadMessages(); });
});

setInterval(loadMessages,2000);
loadMessages();
</script>


