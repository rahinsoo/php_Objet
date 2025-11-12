<?php
global $bdd;
require_once "config/db.php";

// Récupération de toutes les factures avec les informations du client
try {
    $sql = "SELECT f.*, c.nom, c.prenom 
            FROM FACTURE f
            INNER JOIN CLIENTS c ON f.id_client = c.id_client
            ORDER BY f.id_facture DESC";
    $stmt = $bdd->query($sql);
    $factures = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(Exception $e) {
    die("Erreur lors de la récupération des factures : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Liste des Factures</title>
</head>
<body class="bg-dark">
<header class="py-3">
    <div class="container">
        <h1 class="text-light">Liste des Factures</h1>
    </div>
</header>
<main class="container mt-4">
    
    <div class="mb-3">
        <a href="index.php" class="btn btn-light">Gestion des factures</a>
        <a href="add_client.php" class="btn btn-success">Ajouter un client</a>
        <a href="list_clients.php" class="btn btn-secondary">Voir les clients</a>
        <a href="add_facture.php" class="btn btn-info">Créer une facture</a>
        <a href="search_factures.php" class="btn btn-warning">Rechercher</a>
    </div>
    
    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-success">Facture supprimée avec succès !</div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">Erreur lors de la suppression de la facture.</div>
    <?php endif; ?>
    
    <?php if (empty($factures)): ?>
        <div class="alert alert-warning">
            Aucune facture enregistrée. <a href="add_facture.php" class="alert-link">Créer une facture</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-dark">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Produits</th>
                        <th>Quantité</th>
                        <th>Montant</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total = 0;
                    foreach($factures as $facture): 
                        $total += $facture['montant'];
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($facture['id_facture']); ?></td>
                            <td><?php echo htmlspecialchars($facture['nom'] . ' ' . $facture['prenom']); ?></td>
                            <td><?php echo htmlspecialchars(substr($facture['produits'], 0, 50)) . (strlen($facture['produits']) > 50 ? '...' : ''); ?></td>
                            <td><?php echo htmlspecialchars($facture['quantite']); ?></td>
                            <td><?php echo number_format($facture['montant'], 2, ',', ' '); ?> €</td>
                            <td>
                                <a href="edit_facture.php?id=<?php echo $facture['id_facture']; ?>" 
                                   class="btn btn-sm btn-warning">Modifier</a>
                                <a href="delete_facture.php?id=<?php echo $facture['id_facture']; ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette facture ?');">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="table-info">
                        <td colspan="4" class="text-end"><strong>Total :</strong></td>
                        <td colspan="2"><strong><?php echo number_format($total, 2, ',', ' '); ?> €</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <p class="text-light">Total : <?php echo count($factures); ?> facture(s)</p>
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