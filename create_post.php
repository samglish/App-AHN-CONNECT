<?php
// Debug : affichage des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'db.php';
require_once 'functions.php';

// Vérification connexion utilisateur
if (!isset($_SESSION['id'])) {
    $_SESSION['error'] = "Connectez-vous pour accéder à cette page.";
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_post'])) {
    $user_id = (int) $_SESSION['id'];
    $content = sanitize_input($_POST['content'] ?? '');

    // Validation : au moins texte ou fichier
    if (empty($content) && (empty($_FILES['post_files']['name'][0]) || !isset($_FILES['post_files']))) {
        $_SESSION['error'] = "Le contenu du post ou au moins un fichier est requis.";
        header("Location: index.php");
        exit();
    }

    // Préparer l’insertion du post
    $sql = "INSERT INTO posts (user_id, content, created_at) VALUES (?, ?, NOW())";
    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        $_SESSION['error'] = "Erreur SQL (préparation) : " . mysqli_error($conn);
        header("Location: index.php");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "is", $user_id, $content);

    if (!mysqli_stmt_execute($stmt)) {
        $_SESSION['error'] = "Erreur lors de la création du post : " . mysqli_error($conn);
        header("Location: index.php");
        exit();
    }

    // ID du post inséré
    $post_id = mysqli_insert_id($conn);
    mysqli_stmt_close($stmt);

    // Gestion des fichiers uploadés
    if (isset($_FILES['post_files']) && is_array($_FILES['post_files']['name'])) {
        $files = $_FILES['post_files'];

        for ($i = 0; $i < count($files['name']); $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_OK) {
                // Création d’un tableau simulant un seul fichier
                $single_file = [
                    'name' => $files['name'][$i],
                    'type' => $files['type'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'error' => $files['error'][$i],
                    'size' => $files['size'][$i]
                ];

                // Upload via ta fonction
                $upload_result = upload_file($single_file, 'mixed');
                if ($upload_result['success']) {
                    $saved_name = $upload_result['file_name'];
                    $orig_name  = $files['name'][$i];

                    // Enregistre le fichier en BDD
                    $stmt = $conn->prepare("INSERT INTO post_files (post_id, file_name, file_path) VALUES (?, ?, ?)");
                    if ($stmt) {
                        $stmt->bind_param("iss", $post_id, $orig_name, $saved_name);
                        $stmt->execute();
                        $stmt->close();
                    } else {
                        $_SESSION['error'] = "Erreur SQL (upload fichier) : " . $conn->error;
                        header("Location: index.php");
                        exit();
                    }
                } else {
                    $_SESSION['error'] = $upload_result['error'];
                    header("Location: index.php");
                    exit();
                }
            }
        }
    }

    $_SESSION['success'] = "Post créé avec succès !";
    header("Location: index.php");
    exit();
} else {
    $_SESSION['error'] = "Requête invalide.";
    header("Location: index.php");
    exit();
}

