<?php
require_once 'db.php';
require_once 'header.php';
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $message = "Tous les champs sont obligatoires.";
    } elseif ($new_password !== $confirm_password) {
        $message = "Le nouveau mot de passe et la confirmation ne correspondent pas.";
    } else {
        // Vérifier le mot de passe actuel en base
        $sql = "SELECT mot_de_passe FROM etudiants WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!$user || !password_verify($current_password, $user['mot_de_passe'])) {
            $message = "Mot de passe actuel incorrect.";
        } else {
            // Hasher et mettre à jour le nouveau mot de passe
            $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $update_sql = "UPDATE etudiants SET mot_de_passe = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("si", $new_password_hash, $user_id);

            if ($update_stmt->execute()) {
                $message = "Mot de passe changé avec succès !";
            } else {
                $message = "Erreur lors de la mise à jour du mot de passe.";
            }
            $update_stmt->close();
        }
        $stmt->close();
    }
}
?>

<h2>Changer mon mot de passe</h2>

<?php if ($message): ?>
    <p><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form method="post">
    <label>Mot de passe actuel :</label><br>
    <input type="password" name="current_password" required><br><br>

    <label>Nouveau mot de passe :</label><br>
    <input type="password" name="new_password" required><br><br>

    <label>Confirmer nouveau mot de passe :</label><br>
    <input type="password" name="confirm_password" required><br><br>

    <button type="submit" class="btn btn-primary">Changer le mot de passe</button>
</form>

<?php require_once 'footer.php'; ?>
