<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'db.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$my_id = $_SESSION['id'];

// ✅ Charger les amis acceptés
$sql = "SELECT e.id, e.nom, e.prenom, e.photo_profil 
        FROM amis a
        JOIN etudiants e ON (e.id = a.ami_id)
        WHERE a.user_id = ? AND a.statut = 'accepte'";
$stmt = $conn->prepare($sql);
$stmt->execute([$my_id]);
$amis = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ✅ Gérer les envois AJAX
if (isset($_GET['action'])) {
    header('Content-Type: application/json; charset=utf-8');
    $action = $_GET['action'];

    // Charger messages
    if ($action == 'get_messages') {
        $receiver_id = (int)$_GET['receiver_id'];
        $stmt = $conn->prepare("
            SELECT m.*, e.prenom, e.nom, e.photo_profil
            FROM messages m
            JOIN etudiants e ON e.id = m.sender_id
            WHERE (m.sender_id = :me AND m.receiver_id = :other)
               OR (m.sender_id = :other AND m.receiver_id = :me)
            ORDER BY m.date_envoi ASC
        ");
        $stmt->execute(['me' => $my_id, 'other' => $receiver_id]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        exit;
    }

    // Envoyer message
    if ($action == 'send_message') {
        $data = json_decode(file_get_contents('php://input'), true);
        $receiver = (int)$data['receiver_id'];
        $message = trim($data['message']);

        if ($receiver && $message !== '') {
            $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
            $stmt->execute([$my_id, $receiver, $message]);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
        exit;
    }

    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Messages privés — AHN CONNECT</title>
<style>
body {font-family: Arial, sans-serif; background-color:#f4f7fa; margin:0;}
header {background:#002b5b; color:white; padding:10px 20px; display:flex; justify-content:space-between; align-items:center;}
header .user {display:flex; align-items:center; gap:10px;}
header img {width:35px; height:35px; border-radius:50%; object-fit:cover;}
.container {display:flex; max-width:1000px; margin:20px auto; background:white; border-radius:10px; overflow:hidden; box-shadow:0 2px 5px rgba(0,0,0,0.1);}
.sidebar {width:30%; border-right:1px solid #ddd; overflow-y:auto; background:#f9fafb;}
.friend {display:flex; align-items:center; gap:10px; padding:10px; cursor:pointer; border-bottom:1px solid #eee;}
.friend:hover {background:#e6f2ff;}
.friend img {width:40px; height:40px; border-radius:50%; object-fit:cover;}
.chat-area {flex:1; display:flex; flex-direction:column;}
.chat-header {background:#f7f7f7; padding:10px; border-bottom:1px solid #ddd;}
.chat-box {flex:1; padding:15px; overflow-y:auto; background:white; display:flex; flex-direction:column; gap:10px;}
.message {max-width:70%; padding:10px; border-radius:8px; word-wrap:break-word;}
.sent {background:#e6f2ff; align-self:flex-end; border-bottom-right-radius:0;}
.received {background:#f0f0f0; align-self:flex-start; border-bottom-left-radius:0;}
.time {font-size:0.7em; color:#666; text-align:right; margin-top:3px;}
.chat-input {display:flex; border-top:1px solid #ddd;}
.chat-input input {flex:1; padding:10px; border:none; outline:none;}
.chat-input button {background:#002b5b; color:white; border:none; padding:10px 15px; cursor:pointer;}
.empty {text-align:center; color:#888; margin-top:30px;}
</style>
</head>
<body>

<header>
  <div style="display:flex; align-items:center; gap:10px;">
    <div class="logo" style="background:linear-gradient(135deg,#ff6b6b,#4ecdc4);width:40px;height:40px;display:flex;align-items:center;justify-content:center;border-radius:8px;font-weight:bold;">AHN</div>
    <div>Chat Privé</div>
  </div>
  <div class="user">
    <img src="uploads/<?php echo htmlspecialchars($_SESSION['photo_profil'] ?? 'default.jpg'); ?>">
    <span><?php echo htmlspecialchars($_SESSION['prenom'].' '.$_SESSION['nom']); ?></span>
  </div>
</header>

<div class="container">
  <aside class="sidebar" id="friendsList">
    <?php if (count($amis) > 0): ?>
      <?php foreach ($amis as $a): ?>
        <div class="friend" onclick="openChat(<?php echo $a['id']; ?>, '<?php echo htmlspecialchars($a['prenom'].' '.$a['nom']); ?>', '<?php echo htmlspecialchars($a['photo_profil']); ?>')">
          <img src="uploads/<?php echo htmlspecialchars($a['photo_profil']); ?>">
          <div><?php echo htmlspecialchars($a['prenom'].' '.$a['nom']); ?></div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="empty">Aucun ami accepté.</div>
    <?php endif; ?>
  </aside>

  <section class="chat-area">
    <div class="chat-header"><strong id="chatWith">Sélectionne un ami pour discuter</strong></div>
    <div class="chat-box" id="chatBox"><div class="empty">Aucune conversation ouverte.</div></div>
    <div class="chat-input">
      <input type="text" id="messageInput" placeholder="Écris ton message..." onkeypress="if(event.key==='Enter') sendMessage();">
      <button onclick="sendMessage()">Envoyer</button>
    </div>
  </section>
</div>

<script>
let currentReceiver = null;

async function openChat(id, name, photo){
  currentReceiver = id;
  document.getElementById('chatWith').innerText = "Discussion avec " + name;
  loadMessages();
}

async function loadMessages(){
  if(!currentReceiver) return;
  const res = await fetch(`messages.php?action=get_messages&receiver_id=${currentReceiver}`);
  const data = await res.json();
  const box = document.getElementById('chatBox');
  box.innerHTML = '';
  if(data.length === 0){
    box.innerHTML = '<div class="empty">Aucun message pour le moment.</div>';
  } else {
    data.forEach(m=>{
      const msg = document.createElement('div');
      msg.className = 'message ' + (m.sender_id == <?php echo $my_id; ?> ? 'sent':'received');
      msg.innerHTML = `
        <div>${m.message}</div>
        <div class="time">${m.date_envoi}</div>
      `;
      box.appendChild(msg);
    });
    box.scrollTop = box.scrollHeight;
  }
}

async function sendMessage(){
  const input = document.getElementById('messageInput');
  const msg = input.value.trim();
  if(!msg || !currentReceiver) return;
  await fetch('messages.php?action=send_message',{
    method:'POST',
    headers:{'Content-Type':'application/json'},
    body:JSON.stringify({receiver_id:currentReceiver,message:msg})
  });
  input.value='';
  loadMessages();
}

setInterval(()=>{ if(currentReceiver) loadMessages(); }, 3000);
</script>

</body>
</html>

