<?php
session_start();
include 'db.php';

if (!isset($_SESSION['id'])) exit;

$expediteur_id = $_SESSION['id'];
$destinataire_id = intval($_POST['destinataire_id']);
$contenu = trim($_POST['contenu']);

if ($contenu !== "") {
    $stmt = $conn->prepare("INSERT INTO messages (expediteur_id, destinataire_id, contenu, date_envoi) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iis", $expediteur_id, $destinataire_id, $contenu);
    $stmt->execute();
}

