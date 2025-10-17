<?php
require_once 'db.php';

// Fonction d'upload de fichier
function upload_file($file) {
    $target_dir = "uploads/";
    $file_type = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    
    $allowed_types = ['doc','csv','docx','xls','xlsx','ppt','pptx','txt','pdf','jpg','jpeg','png','gif','webp'];
    if (!in_array($file_type, $allowed_types)) {
        return ['success' => false, 'error' => 'Type de fichier non autorisé. Formats acceptés: doc,docx,xls,xlsx,ppt,pptx,txt,pdf,jpg,jpeg,png,gif,webp,csv'];
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
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

   $sql = "
    SELECT p.id, p.content, p.created_at, e.id as user_id, 
           e.prenom, e.nom, e.photo_profil,
           CONCAT(e.prenom, ' ', e.nom) as username
    FROM posts p
    JOIN etudiants e ON p.user_id = e.id
    ORDER BY p.created_at DESC
";

    $result = $conn->query($sql);
    $posts = [];

    while ($row = $result->fetch_assoc()) {
        $post_id = $row['id'];

        // === FICHIERS ===
        $files = [];
        $stmt = $conn->prepare("SELECT file_name, file_path FROM post_files WHERE post_id = ?");
        $stmt->bind_param("i", $post_id);
        $stmt->execute();
        $res_files = $stmt->get_result();
        while ($f = $res_files->fetch_assoc()) {
            $files[] = $f;
        }
        $row['files'] = $files;

        // === LIKES ===
        $stmt = $conn->prepare("SELECT COUNT(*) as c FROM likes WHERE post_id = ?");
        $stmt->bind_param("i", $post_id);
        $stmt->execute();
        $res_likes = $stmt->get_result()->fetch_assoc();
        $row['like_count'] = $res_likes['c'];

        $user_id = $_SESSION['id'] ?? 0;
        $stmt = $conn->prepare("SELECT 1 FROM likes WHERE post_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $post_id, $user_id);
        $stmt->execute();
        $row['user_has_liked'] = $stmt->get_result()->num_rows > 0;

        // === COMMENTAIRES ===
        $comments = [];
        $stmt = $conn->prepare("
            SELECT c.content, c.created_at, e.prenom, e.nom, e.photo_profil,
                   CONCAT(e.prenom, ' ', e.nom) as username
            FROM comments c
            JOIN etudiants e ON c.user_id = e.id
            WHERE c.post_id = ?
            ORDER BY c.created_at ASC
        ");
        $stmt->bind_param("i", $post_id);
        $stmt->execute();
        $res_comments = $stmt->get_result();
        while ($c = $res_comments->fetch_assoc()) {
            $comments[] = $c;
        }
        $row['comments'] = $comments;
        $row['comment_count'] = count($comments);

        $posts[] = $row;
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

// Fonction like

?>
