<?php
session_start();
include 'db.php';
include 'header.php';       // ton header AHN CONNECT


if(!isset($_SESSION['id'])){
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
if($destinataire_id > 0){
    $stmt = $conn->prepare("SELECT id, nom, prenom, photo_profil FROM etudiants WHERE id=?");
    $stmt->bind_param("i", $destinataire_id);
    $stmt->execute();
    $destinataire = $stmt->get_result()->fetch_assoc();
}
?>

<!-- === CSS intégrée directement === -->
<style>
/* Container */
.container-messages {
  display:flex;
  flex-wrap:wrap;
  gap:20px;
  padding:15px;
  min-height:80vh;
  box-sizing:border-box;
  overflow:hidden;
}

/* Sidebar amis */
.sidebar {
  flex:1 1 250px;
  max-width:250px;
  background:#f1f1f1;
  padding:10px;
  border-radius:8px;
  overflow-y:auto;
}

.sidebar h3 {
  background:#0066cc;
  color:white;
  padding:10px;
  margin:0;
  border-radius:12px 12px 0 0;
  text-align:center;
}

.sidebar li {
  display:flex;
  align-items:center;
  padding:10px;
  border-bottom:1px solid #f1f1f1;
  cursor:pointer;
  transition: background 0.2s;
}

.sidebar li:hover {
  background:#f5faff;
}

.sidebar img {
  width:40px;
  height:40px;
  border-radius:50%;
  margin-right:10px;
  object-fit:cover;
}

/* Zone de chat */
.chat {
  flex:3 1 500px;
  display:flex;
  flex-direction:column;
  background:#fff;
  border-radius:12px;
  overflow:hidden;
  height:100%;
  box-shadow:0 2px 6px rgba(0,0,0,0.1);
}

.header-chat {
  background:#0066cc;
  color:white;
  padding:10px 15px;
  font-weight:bold;
  border-radius:12px 12px 0 0;
}

/* Messages */
.messages {
  flex:1;
  padding:10px;
  overflow-y:auto;
  display:flex;
  flex-direction:column;
  gap:10px;
  background:#f8f9fa;
}

.message {
  max-width:75%;
  padding:10px;
  border-radius:15px;
  word-wrap:break-word;
}

.message.exp {
  background:#0066cc;
  color:white;
  align-self:flex-end;
  border-bottom-right-radius:0;
}

.message.dest {
  background:#e5e5ea;
  align-self:flex-start;
  border-bottom-left-radius:0;
}

/* Formulaire d’envoi */
.form-message {
  display:flex;
  border-top:1px solid #ddd;
  padding:10px;
  background:#fff;
  border-radius:0 0 12px 12px;
}

.form-message input {
  flex:1;
  border:1px solid #ccc;
  border-radius:25px;
  padding:10px 15px;
  outline:none;
  transition:border-color 0.2s;
}

.form-message input:focus {
  border-color:#0066cc;
}

.form-message button {
  margin-left:10px;
  border:none;
  background:#0066cc;
  color:white;
  border-radius:25px;
  padding:10px 20px;
  cursor:pointer;
  transition:background 0.2s;
}

.form-message button:hover {
  background:#004c99;
}

/* Responsive */
@media(max-width:768px){
  .container-messages { display:grid; grid-template-columns:1fr 2fr; height:calc(100vh - 150px);}
  .sidebar { width:100%; max-width:none;}
  .chat { width:100%;}
}

@media(max-width:480px){
  .container-messages { grid-template-columns:1fr; height:auto;}
}
</style>

<!-- === HTML Messagerie === -->
<div class="container-messages">
    <!-- Sidebar amis -->
    <ul class="sidebar">
        <h3>Mes amis</h3>
        <?php foreach($amis as $a): ?>
            <li>
                <img src="<?= htmlspecialchars($a['photo_profil']) ?>" alt="">
                <a href="messages.php?id=<?= $a['id'] ?>" style="text-decoration:none; color:#333;">
                    <?= htmlspecialchars($a['prenom'].' '.$a['nom']) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

    <!-- Zone de chat -->
    <div class="chat">
        <?php if($destinataire): ?>
            <div class="header-chat"><?= htmlspecialchars($destinataire['prenom'].' '.$destinataire['nom']) ?></div>
            <div id="messages" class="messages"></div>
            <form id="sendForm" class="form-message">
                <input type="text" id="message" placeholder="Écrire un message...">
                <input type="hidden" id="dest_id" value="<?= $destinataire_id ?>">
                <button type="submit">Envoyer</button>
            </form>
        <?php else: ?>
            <p style="padding:10px;">Sélectionnez un ami pour discuter.</p>
        <?php endif; ?>
    </div>
</div>

<!-- === JS pour messages en temps réel === -->
<script>
function loadMessages(){
    let dest_id = document.getElementById('dest_id').value;
    fetch('fetch_messages.php?id='+dest_id)
        .then(res => res.text())
        .then(data => {
            let chatBox = document.getElementById('messages');
            chatBox.innerHTML = data;
            chatBox.scrollTop = chatBox.scrollHeight;
        });
}

document.getElementById('sendForm')?.addEventListener('submit', function(e){
    e.preventDefault();
    let msg = document.getElementById('message').value.trim();
    let dest_id = document.getElementById('dest_id').value;
    if(msg==='') return;
    let formData = new FormData();
    formData.append('contenu', msg);
    formData.append('destinataire_id', dest_id);
    fetch('send_message.php',{method:'POST',body:formData})
        .then(()=>{ document.getElementById('message').value=''; loadMessages(); });
});

setInterval(loadMessages,2000);
loadMessages();
</script>


