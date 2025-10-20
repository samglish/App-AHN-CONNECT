<?php
session_start();
include 'db.php';

if (!isset($_SESSION['id'])) exit;

$user_id = $_SESSION['id'];
$destinataire_id = intval($_GET['id']);

$stmt = $conn->prepare("
    SELECT m.*, e.prenom AS expediteur_prenom
    FROM messages m
    JOIN etudiants e ON e.id = m.expediteur_id
    WHERE (m.expediteur_id = ? AND m.destinataire_id = ?)
       OR (m.expediteur_id = ? AND m.destinataire_id = ?)
    ORDER BY m.date_envoi ASC
");
$stmt->bind_param("iiii", $user_id, $destinataire_id, $destinataire_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($msg = $result->fetch_assoc()) {
    $classe = ($msg['expediteur_id'] == $user_id) ? 'sent' : 'received';
    echo "<div class='message $classe'>";
    echo "<strong>" . htmlspecialchars($msg['expediteur_prenom']) . ":</strong><br>";
    echo nl2br(htmlspecialchars($msg['contenu'])) . "<br>";
    echo "<small>" . htmlspecialchars($msg['date_envoi']) . "</small>";
    echo "</div>";
}

