<?php
session_start();
ob_start();
require_once 'db.php';
require_once 'header.php';
require_once 'functions.php';

if (!isset($_SESSION['id'])) {
    $_SESSION['error'] = "Connectez-vous pour accéder à cette page.";
    header("Location: login.php");
    exit();
    ob_end_flush();
}

$user_id = $_SESSION['id'];
$nom = $_SESSION['nom'];
$prenom = $_SESSION['prenom'];
$photo = $profile_pic;

if (isset($_POST['envoyer'])) {
    $message = trim($_POST['message']);
    if (!empty($message)) {
        $sql = "INSERT INTO chat (user_id, nom, prenom, photo, message) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issss", $user_id, $nom, $prenom, $photo, $message);
        $stmt->execute();
    }
    header("Location: chat.php");
    exit();
}

$sql = "SELECT * FROM chat ORDER BY created_at ASC LIMIT 50";
$result = $conn->query($sql);
?>

<style>
    .chat-container {
        width: 100%;
        max-width: 800px;
        margin: 0 auto;
        padding: 10px;
    }

    .chat-box {
        height: 400px;
        overflow-y: auto;
        border: 1px solid #ccc;
        background-color: #f9f9f9;
        padding: 10px;
        border-radius: 8px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .message {
        display: flex;
        max-width: 100%;
        position: relative;
    }

    .message.other {
        justify-content: flex-start;
    }

    .message.me {
        justify-content: flex-end;
    }

    .message-content {
        display: flex;
        align-items: flex-end;
        gap: 8px;
        max-width: 80%;
    }

    .message-bubble {
        padding: 10px;
        border-radius: 10px;
        background-color: #e9ecef;
        max-width: 100%;
        word-wrap: break-word;
        font-size: 0.95em;
        position: relative;
    }

    .me .message-bubble {
        background-color: #cce5ff;
    }

    .message img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
    }

    .timestamp {
        font-size: 0.75em;
        color: gray;
        text-align: right;
        margin-top: 5px;
    }

    .delete-btn {
        background: none;
        border: none;
        color: red;
        cursor: pointer;
        font-size: 14px;
        margin-left: 8px;
    }

    /* 📱 Optimisation mobile */
    @media (max-width: 600px) {
        .chat-container {
            width: 100%;
            max-width: 100%;
            padding: 5px;
        }

        .chat-box {
            height: 70vh; /* occupe 70% de la hauteur écran */
            padding: 8px;
        }

        .message-content {
            flex-direction: column;
            align-items: flex-start;
            max-width: 100%;
        }

        .me .message-content {
            align-items: flex-end;
        }

        .message-bubble {
            font-size: 0.85em;
        }

        .message img {
            width: 30px;
            height: 30px;
        }
    }
</style>

<div class="chat-container">
    <div id="chat-box" class="chat-box">
        <?php while ($row = $result->fetch_assoc()): ?>
            <?php $is_me = ($row['user_id'] == $user_id); ?>
            <div class="message <?= $is_me ? 'me' : 'other' ?>" id="msg-<?= $row['id'] ?>">
                <div class="message-content">
                    <?php if (!$is_me): ?>
                        <img src="uploads/<?= htmlspecialchars($row['photo']) ?>" alt="Photo">
                    <?php endif; ?>

                    <div class="message-bubble">
                        <strong><?= htmlspecialchars($row['prenom'] . ' ' . $row['nom']) ?></strong><br>
                        <?= htmlspecialchars($row['message']) ?>
                        <div class="timestamp"><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></div>

                        <?php if ($is_me): ?>
                            <button class="delete-btn" onclick="deleteMessage(<?= $row['id'] ?>)"><font size="2px">supprimer🗑️</font></button>
                        <?php endif; ?>
                    </div>

                    <?php if ($is_me): ?>
                        <img src="uploads/<?= $profile_pic ?>" alt="Profile">
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <form method="POST" action="chat.php" class="comment-form" style="margin-top: 15px; display: flex; gap: 10px;">
        <input type="text" name="message" placeholder="Écrivez un message..." required style="
            flex: 1;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        ">
        <button type="submit" name="envoyer" class="btn btn-primary">Envoyer</button>
    </form>
</div>

<script>
    const chatBox = document.getElementById('chat-box');
    chatBox.scrollTop = chatBox.scrollHeight;

    function deleteMessage(id) {
        if (!confirm("Voulez-vous vraiment supprimer ce message ?")) return;

        fetch("delete_message.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "id=" + id
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById("msg-" + id).remove();
            } else {
                alert(data.message || "Erreur lors de la suppression");
            }
        })
        .catch(err => alert("Erreur réseau"));
    }
</script>
