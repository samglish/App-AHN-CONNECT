<?php
session_start();
require_once 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Non connecté']);
    exit;
}

$userId = $_SESSION['id'];

if (!isset($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID manquant']);
    exit;
}

$messageId = intval($_POST['id']);

// Vérifier que le message appartient bien à l’utilisateur
$stmt = $conn->prepare("SELECT user_id FROM chat WHERE id = ?");
$stmt->bind_param("i", $messageId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Message introuvable']);
    exit;
}

$row = $result->fetch_assoc();
if ($row['user_id'] != $userId) {
    echo json_encode(['success' => false, 'message' => 'Vous ne pouvez pas supprimer ce message']);
    exit;
}

// Supprimer le message
$stmt = $conn->prepare("DELETE FROM chat WHERE id = ?");
$stmt->bind_param("i", $messageId);
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression']);
}
