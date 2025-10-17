<?php
require_once 'db.php';

// Fonction d'upload de fichier
function upload_file($file) {
    $target_dir = "uploads/";
    $file_type = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'txt', 'zip'];
    if (!in_array($file_type, $allowed_types)) {
        return ['success' => false, 'error' => 'Type de fichier non autorisé. Formats acceptés: jpg, png, gif, pdf, txt, zip'];
    }
    
    if ($file["size"] > 50000000) {
        return ['success' => false, 'error' => 'Fichier trop volumineux. Taille maximale: 50MB'];
    }
    
    $file_name = uniqid() . '.' . $file_type;
    $target_file = $target_dir . $file_name;
    
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return ['success' => true, 'file_name' => $file_name];
    } else {
        return ['success' => false, 'error' => 'Erreur lors du téléchargement du fichier'];
    }
}

// Récupérer les posts avec les commentaires
function get_posts($conn) {
    $posts_sql = "SELECT posts.*, etudiants.nom, etudiants.prenom, etudiants.photo_profil, 
                 (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) AS comment_count
                 FROM posts
                 JOIN etudiants ON posts.user_id = etudiants.id
                 ORDER BY posts.created_at DESC";
    $posts_result = mysqli_query($conn, $posts_sql);
    $posts = [];
    if ($posts_result) {
        while ($row = mysqli_fetch_assoc($posts_result)) {
            $post_id = $row['id'];
            $comments_sql = "SELECT comments.*, etudiants.nom, etudiants.prenom, etudiants.photo_profil 
                             FROM comments 
                             JOIN etudiants ON comments.user_id = etudiants.id 
                             WHERE post_id = $post_id 
                             ORDER BY comments.created_at ASC";
            $comments_result = mysqli_query($conn, $comments_sql);
            $comments = [];
            if ($comments_result) {
                while ($comment_row = mysqli_fetch_assoc($comments_result)) {
                    $comment_row['username'] = $comment_row['prenom'] . ' ' . $comment_row['nom'];
                    $comments[] = $comment_row;
                }
            }
            $row['comments'] = $comments;
            $row['username'] = $row['prenom'] . ' ' . $row['nom'];
            $posts[] = $row;
        }
    }
    return $posts;
}

// Récupérer les actualités du département
function get_news($conn) {
    $news_sql = "SELECT * FROM department_news ORDER BY created_at DESC";
    $news_result = mysqli_query($conn, $news_sql);
    $news = [];
    if ($news_result) {
        while ($row = mysqli_fetch_assoc($news_result)) {
            $news[] = $row;
        }
    }
    return $news;
}

// Récupérer les utilisateurs
function get_users($conn) {
    $users_sql = "SELECT id, nom, prenom, photo_profil, filiere FROM etudiants";
    $users_result = mysqli_query($conn, $users_sql);
    $users = [];
    if ($users_result) {
        while ($row = mysqli_fetch_assoc($users_result)) {
            $row['username'] = $row['prenom'] . ' ' . $row['nom'];
            $row['level'] = $row['filiere'];
            $users[] = $row;
        }
    }
    return $users;
}

// Fonction de nettoyage des données
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
