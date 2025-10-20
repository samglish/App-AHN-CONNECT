<?php
session_start();
include 'db.php';
if(!isset($_SESSION['id'])) exit;

$mon_id = $_SESSION['id'];
$dest_id = intval($_GET['id']);

$stmt = $conn->prepare("
    SELECT m.*, e.prenom, e.nom
    FROM messages m
    JOIN etudiants e ON m.expediteur_id = e.id
    WHERE (m.expediteur_id=? AND m.destinataire_id=?) 
       OR (m.expediteur_id=? AND m.destinataire_id=?)
    ORDER BY m.date_envoi ASC
");
$stmt->bind_param("iiii",$mon_id,$dest_id,$dest_id,$mon_id);
$stmt->execute();
$res = $stmt->get_result();

while($m = $res->fetch_assoc()){
    $cls = ($m['expediteur_id'] == $mon_id) ? 'me' : 'friend';
    echo "<div class='message $cls' style='padding:8px; margin-bottom:8px; border-radius:12px; max-width:70%; ";
    echo ($cls=='me') ? "background:#007bff; color:white; margin-left:auto; text-align:right;" : "background:#f1f1f1; margin-right:auto; text-align:left;";
    echo "'>";
    echo "<strong>".htmlspecialchars($m['prenom']).":</strong> ".htmlspecialchars($m['contenu'])."<br>";
    echo "<small>".$m['date_envoi']."</small></div>";
}

