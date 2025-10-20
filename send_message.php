<?php
session_start();
include 'db.php';
if(!isset($_SESSION['id'])) exit;

$expediteur = $_SESSION['id'];
$destinataire = intval($_POST['destinataire_id']);
$contenu = trim($_POST['contenu']);

if($contenu!==''){
    $stmt = $conn->prepare("INSERT INTO messages (expediteur_id, destinataire_id, contenu) VALUES (?, ?, ?)");
    $stmt->bind_param("iis",$expediteur,$destinataire,$contenu);
    $stmt->execute();
}

