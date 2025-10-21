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
//echo "<p>ID utilisateur connecté : $user_id</p>";
echo "</br>
 <h4><a href="news.php" class="btn btn-primary">Voir les actualités du département</a> </br></br></h4>
   
";

// ===================================================
// ÉTAPE 2 : FORMULAIRE POUR AJOUTER DES AMIS
// ===================================================

echo "<h3 class='mt-4'>Ajouter des amis</h3> <div id='Formulaire'>";
echo "<form method='POST' class='add-friend-form'>
    <div class='input-group mb-3'>
        <input type='text' class='form-control' placeholder='Entrez un matricule' name='matricule' required>
        <button class='btn btn-primary' type='submit' name='add_friend'>Envoyer la demande</button>
    </div></div>
</form>";

if (isset($_POST['add_friend'])) {
    $matricule = $_POST['matricule'] ?? '';
    if (!empty($matricule)) {
        $stmt = $conn->prepare("SELECT id FROM etudiants WHERE matricule = ?");
        $stmt->bind_param("s", $matricule);
        $stmt->execute();
        $result = $stmt->get_result();
        $target = $result->fetch_assoc();
        if ($target) {
            $target_id = $target['id'];
            $check = $conn->prepare("SELECT id FROM amis 
                                    WHERE (user_id = ? AND ami_id = ?)
                                    OR (user_id = ? AND ami_id = ?)");
            $check->bind_param("iiii", $user_id, $target_id, $target_id, $user_id);
            $check->execute();
            if ($check->get_result()->num_rows == 0) {
                $insert = $conn->prepare("INSERT INTO amis (user_id, ami_id, statut) 
                                        VALUES (?, ?, 'en_attente')");
                $insert->bind_param("ii", $user_id, $target_id);
                $insert->execute();
                echo "<div class='alert alert-success'>Demande envoyée à $matricule</div>";
            } else {
                echo "<div class='alert alert-warning'>Relation déjà existante</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Aucun étudiant trouvé avec ce matricule</div>";
        }
    }
}

// ===================================================
// ÉTAPE 3 : AFFICHER LES DEMANDES EN ATTENTE
// ===================================================

$pending = $conn->query("
    SELECT e.id, e.nom, e.prenom, e.matricule, a.id as request_id 
    FROM amis a 
    JOIN etudiants e ON a.user_id = e.id 
    WHERE a.ami_id = $user_id AND a.statut = 'en_attente'
");

if ($pending && $pending->num_rows > 0) {
    echo "<h3 class='mt-4'>Demandes en attente</h3>";
    echo "<div class='pending-requests'>";
    while ($req = $pending->fetch_assoc()) {
        echo "
        <div class='request-card'>
            <div class='request-info'>
                <strong>{$req['prenom']} {$req['nom']}</strong> ({$req['matricule']})
            </div>
            <div class='request-actions'>
                <a href='handle_friend.php?action=accept&id={$req['request_id']}' class='btn btn-sm btn-success'>Accepter</a>
                <a href='handle_friend.php?action=reject&id={$req['request_id']}' class='btn btn-sm btn-danger'>Refuser</a>
            </div>
        </div>";
    }
    echo "</div>";
} else {
    echo "<p class='mt-4'>Aucune demande d'ami en attente</p>";
}


// ===================================================
// ÉTAPE 1 : AFFICHER LES AMIS
// ===================================================

$sql = "
    SELECT e.id, e.nom, e.prenom, e.photo_profil, e.matricule
    FROM amis a
    JOIN etudiants e ON 
        (a.ami_id = e.id AND a.user_id = ? AND a.statut = 'accepte')
        OR 
        (a.user_id = e.id AND a.ami_id = ? AND a.statut = 'accepte')
    GROUP BY e.id
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Erreur préparation requête : " . $conn->error);
}
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

echo "<h3>Mes amis</h3>";
if ($result->num_rows == 0) {
    echo "<div class='alert alert-info'>Aucun ami trouvé. Ajoutez des amis ci-dessous.</div>";
} else {
    echo "<div class='friends-list'>";
    while ($row = $result->fetch_assoc()) {
        $photo = !empty($row['photo_profil']) ? $row['photo_profil'] : 'default.jpg';
        echo "
<div class='friend-card'>
    <a href='profile.php?id={$row['id']}'>
        <img src='uploads/{$photo}' alt='Photo profil' class='friend-photo'>
    </a>
    <div class='friend-info'>
        <h5><a href='profile.php?id={$row['id']}'>{$row['prenom']} {$row['nom']}</a></h5>
        <p class='text-muted'>Matricule: {$row['matricule']}</p>
    </div>
    <div class='friend-actions'>
        <form method='POST' style='display:inline'>
            <input type='hidden' name='friend_id' value='{$row['id']}'>
            <button type='submit' name='remove_friend' class='btn btn-sm btn-outline-danger'>Supprimer</button>
        </form>
    </div>
</div>";
    }
    echo "</div>";
}

// Suppression d'ami
if (isset($_POST['remove_friend'])) {
    $friend_id = $_POST['friend_id'];
    $conn->query("DELETE FROM amis WHERE 
                (user_id = $user_id AND ami_id = $friend_id) 
                OR 
                (user_id = $friend_id AND ami_id = $user_id)");
    echo "<div class='alert alert-success'>Ami supprimé avec succès</div>";
    echo "<script>setTimeout(() => window.location.reload(), 1500)</script>";
}

// ===================================================
// ÉTAPE 4 : AFFICHER TOUS LES UTILISATEURS DISPONIBLES
// ===================================================

$sql_all = "
    SELECT e.id, e.nom, e.prenom, e.matricule, e.photo_profil
    FROM etudiants e
    WHERE e.id != ?
    AND e.id NOT IN (
        SELECT ami_id FROM amis WHERE user_id = ? 
        UNION 
        SELECT user_id FROM amis WHERE ami_id = ?
    )
";

$stmt_all = $conn->prepare($sql_all);
$stmt_all->bind_param("iii", $user_id, $user_id, $user_id);
$stmt_all->execute();
$others = $stmt_all->get_result();

echo "<h3 class='mt-4'>Autres utilisateurs</h3>";
if ($others->num_rows == 0) {
    echo "<div class='alert alert-info'>Aucun utilisateur disponible.</div>";
} else {
    echo "<div class='friends-list'>";
    while ($row = $others->fetch_assoc()) {
        $photo = !empty($row['photo_profil']) ? $row['photo_profil'] : 'default.jpg';
        echo "
        <div class='friend-card'>
            <a href='profileNA.php?id={$row['id']}'>
                <img src='uploads/{$photo}' alt='Photo profil' class='friend-photo'>
            </a>
            <div class='friend-info'>
                <h5><a href='profileNA.php?id={$row['id']}'>{$row['prenom']} {$row['nom']}</a></h5>
                <p class='text-muted'>Matricule: {$row['matricule']}</p>
            </div>
            <div class='friend-actions'>
                <form method='POST'>
                    <input type='hidden' name='matricule' value='{$row['matricule']}'>
                    <button type='submit' name='add_friend' class='btn btn-sm btn-primary'>Envoyer la demande</button>
                </form>
            </div>
        </div>";
    }
    echo "</div>";
}



// ===================================================
// CSS
// ===================================================
?>
<style>
    .friends-list, .pending-requests {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-bottom: 30px;
    }
    .friend-card, .request-card {
        display: flex;
        align-items: center;
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .friend-photo {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 15px;
        border: 2px solid #eee;
    }
    .friend-info { flex: 1; }
    .friend-actions, .request-actions {
        margin-left: auto;
        display: flex;
        gap: 10px;
    }
</style>
