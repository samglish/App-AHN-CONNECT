<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['id'])) {
    die("Non autorisé");
}

$action = $_GET['action'] ?? '';
$request_id = $_GET['id'] ?? 0;

if ($action && $request_id) {
    if ($action === 'accept') {
        $conn->query("UPDATE amis SET statut = 'accepte' WHERE id = $request_id");
        // Créer la relation inverse
        $request = $conn->query("SELECT user_id, ami_id FROM amis WHERE id = $request_id")->fetch_assoc();
        $user_id = $request['user_id'];
        $ami_id = $request['ami_id'];
        $conn->query("INSERT IGNORE INTO amis (user_id, ami_id, statut) VALUES ($ami_id, $user_id, 'accepte')");
    } elseif ($action === 'reject') {
        $conn->query("DELETE FROM amis WHERE id = $request_id");
    }
}

header("Location: amis.php");
exit();
?>