<?php
global $bdd;
require_once "config/db.php";

// Vérification de la présence de l'ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: list_factures.php");
    exit();
}

$id_facture = $_GET['id'];

// Suppression de la facture
try {
    $sql = "DELETE FROM FACTURE WHERE id_facture = :id";
    $stmt = $bdd->prepare($sql);
    $verif = $stmt->execute(['id' => $id_facture]);
    
    if ($verif) {
        // Redirection avec message de succès
        header("Location: list_factures.php?deleted=1");
        exit();
    } else {
        header("Location: list_factures.php?error=1");
        exit();
    }
} catch(Exception $e) {
    die("Erreur lors de la suppression : " . $e->getMessage());
}
?>