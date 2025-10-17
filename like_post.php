<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Vous devez être connecté.']);
    exit;
}

$user_id = $_SESSION['id'];
$post_id = $_POST['post_id'];

// Vérifier si l'utilisateur a déjà liké ce post
$sql = "SELECT 1 FROM likes WHERE user_id = ? AND post_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $post_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Enlever le like
    $sql = "DELETE FROM likes WHERE user_id = ? AND post_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $post_id);
    $stmt->execute();
    $user_has_liked = false;
} else {
    // Ajouter un like
    $sql = "INSERT INTO likes (user_id, post_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $post_id);
    $stmt->execute();
    $user_has_liked = true;
}

// Récupérer le nouveau nombre de likes
$sql = "SELECT COUNT(*) FROM likes WHERE post_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$stmt->bind_result($like_count);
$stmt->fetch();

echo json_encode([
    'success' => true,
    'like_count' => $like_count,
    'user_has_liked' => $user_has_liked
]);
?>
