<?php
global $bdd;
require_once "config/db.php";

// Statistiques
try {
    // Nombre de clients
    $sql = "SELECT COUNT(*) as nb_clients FROM CLIENTS";
    $stmt = $bdd->query($sql);
    $nb_clients = $stmt->fetch(PDO::FETCH_ASSOC)['nb_clients'];

    // Nombre de factures
    $sql = "SELECT COUNT(*) as nb_factures, SUM(montant) as total_montant FROM FACTURE";
    $stmt = $bdd->query($sql);
    $stats_factures = $stmt->fetch(PDO::FETCH_ASSOC);
    $nb_factures = $stats_factures['nb_factures'];
    $total_montant = $stats_factures['total_montant'] ?? 0;

} catch (Exception $e) {
    die("Erreur : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Gestion des Factures</title>
</head>
<body class="bg-dark">
<header class="py-4">
    <div class="container">
        <h1 class="text-light text-center">Système de Gestion des Factures</h1>
        <p class="text-light text-center">Application PHP PDO / MySQL</p>
    </div>
</header>
<main class="container mt-5">

    <!-- Statistiques -->
    <div class="row mb-5">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h3><?php echo $nb_clients; ?></h3>
                    <p class="mb-0">Clients enregistrés</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h3><?php echo $nb_factures; ?></h3>
                    <p class="mb-0">Factures créées</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h3><?php echo number_format($total_montant, 2, ',', ' '); ?> €</h3>
                    <p class="mb-0">Chiffre d'affaires total</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu principal -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card bg-secondary">
                <div class="card-body">
                    <h5 class="card-title text-light">Gestion des Clients</h5>
                    <p class="card-text text-light">Ajoutez et consultez vos clients</p>
                    <a href="add_client.php" class="btn btn-success me-2">Ajouter un client</a>
                    <a href="list_clients.php" class="btn btn-outline-light">Liste des clients</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card bg-secondary">
                <div class="card-body">
                    <h5 class="card-title text-light">Gestion des Factures</h5>
                    <p class="card-text text-light">Créez, modifiez et consultez vos factures</p>
                    <a href="add_facture.php" class="btn btn-info me-2">Créer une facture</a>
                    <a href="list_factures.php" class="btn btn-outline-light">Liste des factures</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card bg-secondary">
                <div class="card-body">
                    <h5 class="card-title text-light">Recherche avancée (Bonus)</h5>
                    <p class="card-text text-light">Recherchez des factures par client ou par date</p>
                    <a href="list_factures.php" class="btn btn-warning">Rechercher des factures</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Instructions -->
    <div class="alert alert-light mt-5">
        <h5>Instructions :</h5>
        <ol>
            <li>Commencez par ajouter des clients</li>
            <li>Créez ensuite des factures en associant un client</li>
            <li>Consultez, modifiez ou supprimez vos factures</li>
            <li>Utilisez la recherche pour filtrer vos factures</li>
        </ol>
    </div>
</main>
<footer class="mt-5">
    <div class="container-fluid">
        <div class="row text-center text-bg-dark py-3">
            <div class="col">Créé par Xavier en 2025</div>
        </div>
    </div>
</footer>
</body>
</html>
