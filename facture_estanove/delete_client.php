<?php
global $bdd;
require_once "config/db.php";

// Vérification de la présence de l'ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: list_clients.php");
    exit();
}

$id_client = $_GET['id'];

// Vérifier si le client a des factures
try {
    $sql = "SELECT COUNT(*) as nb_factures FROM FACTURE WHERE id_client = :id";
    $stmt = $bdd->prepare($sql);
    $stmt->execute(['id' => $id_client]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $nb_factures = $result['nb_factures'];
    
    if ($nb_factures > 0) {
        // Le client a des factures, on ne peut pas le supprimer (ou alors supprimer les factures aussi)
        header("Location: list_clients.php?error=has_factures&nb=" . $nb_factures);
        exit();
    }
    
    // Suppression du client
    $sql = "DELETE FROM CLIENTS WHERE id_client = :id";
    $stmt = $bdd->prepare($sql);
    $verif = $stmt->execute(['id' => $id_client]);
    
    if ($verif) {
        header("Location: list_clients.php?deleted=1");
        exit();
    } else {
        header("Location: list_clients.php?error=1");
        exit();
    }
} catch(Exception $e) {
    die("Erreur lors de la suppression : " . $e->getMessage());
}
?>
