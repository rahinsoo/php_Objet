<?php
global $bdd;
require_once "config/db.php";

// Récupération de tous les clients
try {
    $sql = "SELECT * FROM CLIENTS ORDER BY nom, prenom";
    $stmt = $bdd->query($sql);
    $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(Exception $e) {
    die("Erreur lors de la récupération des clients : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Liste des Clients</title>
</head>
<body class="bg-dark">
<header class="py-3">
    <div class="container">
        <h1 class="text-light">Liste des Clients</h1>
    </div>
</header>
<main class="container mt-4">
    
    <div class="mb-3">
        <a href="index.php" class="btn btn-light">Gestion des factures</a>
        <a href="add_client.php" class="btn btn-success">Ajouter un client</a>
        <a href="add_facture.php" class="btn btn-info">Créer une facture</a>
        <a href="list_factures.php" class="btn btn-primary">Voir les factures</a>
    </div>
    
    <?php if (empty($clients)): ?>
        <div class="alert alert-warning">
            Aucun client enregistré. <a href="add_client.php">Ajouter un client</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-dark">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Sexe</th>
                        <th>Date de naissance</th>
                        <th>Âge</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($clients as $client): 
                        // Calcul de l'âge
                        $dateNaissance = new DateTime($client['date_naissance']);
                        $aujourdhui = new DateTime();
                        $age = $aujourdhui->diff($dateNaissance)->y;
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($client['id_client']); ?></td>
                            <td><?php echo htmlspecialchars($client['nom']); ?></td>
                            <td><?php echo htmlspecialchars($client['prenom']); ?></td>
                            <td><?php echo htmlspecialchars($client['sexe']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($client['date_naissance'])); ?></td>
                            <td><?php echo $age; ?> ans</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <p class="text-light">Total : <?php echo count($clients); ?> client(s)</p>
    <?php endif; ?>
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