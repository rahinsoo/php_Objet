<?php
global $bdd;
require_once "config/db.php";

// Récupération de la liste des clients pour le filtre
try {
    $sql = "SELECT id_client, nom, prenom FROM CLIENTS ORDER BY nom, prenom";
    $stmt = $bdd->query($sql);
    $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Erreur : " . $e->getMessage());
}

$factures = [];
$searched = false;

// Traitement de la recherche
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $searched = true;

    $date_debut = $_POST['date_debut'] ?? '';
    $date_fin = $_POST['date_fin'] ?? '';
    $id_client = $_POST['id_client'] ?? '';

    // Construction de la requête SQL dynamique
    $sql = "SELECT f.*, c.nom, c.prenom 
            FROM FACTURE f
            INNER JOIN CLIENTS c ON f.id_client = c.id_client
            WHERE 1=1";

    $params = [];

    // Filtre par client
    if (!empty($id_client)) {
        $sql .= " AND f.id_client = :id_client";
        $params['id_client'] = $id_client;
    }

    // Note : Les factures n'ont pas de date dans la structure actuelle
    // Si vous souhaitez ajouter un filtre par date, il faudrait ajouter un champ date_facture dans la table FACTURE

    $sql .= " ORDER BY f.id_facture DESC";

    try {
        $stmt = $bdd->prepare($sql);
        $stmt->execute($params);
        $factures = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        die("Erreur lors de la recherche : " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Rechercher des Factures</title>
</head>
<body class="bg-dark">
<header class="py-3">
    <div class="container">
        <h1 class="text-light">Rechercher des Factures</h1>
    </div>
</header>
<main class="container mt-4">

    <div class="mb-3">
        <a href="index.php" class="btn btn-light">Gestion des factures</a>
        <a href="list_factures.php" class="btn btn-primary">Toutes les factures</a>
        <a href="add_facture.php" class="btn btn-info">Créer une facture</a>
    </div>

    <div class="card bg-secondary mb-4">
        <div class="card-body">
            <h5 class="card-title text-light">Filtres de recherche</h5>
            <form action="search_factures.php" method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="id_client" class="form-label text-light">Client :</label>
                        <select class="form-control" id="id_client" name="id_client">
                            <option value="">-- Tous les clients --</option>
                            <?php foreach ($clients as $client): ?>
                                <option value="<?php echo $client['id_client']; ?>"
                                        <?php echo (isset($_POST['id_client']) && $_POST['id_client'] == $client['id_client']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($client['nom'] . ' ' . $client['prenom']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Note : Ces champs de date ne fonctionneront que si vous ajoutez un champ date_facture dans la table -->
                    <div class="col-md-3 mb-3">
                        <label for="date_debut" class="form-label text-light">Date début :</label>
                        <input type="date" class="form-control" id="date_debut" name="date_debut"
                               value="<?php echo htmlspecialchars($_POST['date_debut'] ?? ''); ?>" disabled>
                        <small class="text-muted">Non disponible sans champ date_facture</small>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="date_fin" class="form-label text-light">Date fin :</label>
                        <input type="date" class="form-control" id="date_fin" name="date_fin"
                               value="<?php echo htmlspecialchars($_POST['date_fin'] ?? ''); ?>" disabled>
                        <small class="text-muted">Non disponible sans champ date_facture</small>
                    </div>
                </div>

                <button type="submit" class="btn btn-success">Rechercher</button>
                <a href="search_factures.php" class="btn btn-secondary">Réinitialiser</a>
            </form>
        </div>
    </div>

    <?php if ($searched): ?>
        <?php if (empty($factures)): ?>
            <div class="alert alert-warning">
                Aucune facture ne correspond à vos critères de recherche.
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
                    foreach ($factures as $facture):
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
            <p class="text-light">Résultats : <?php echo count($factures); ?> facture(s)</p>
        <?php endif; ?>
    <?php endif; ?>

    <div class="alert alert-info mt-4">
        <strong>Note :</strong> Pour activer la recherche par dates, vous devez ajouter un champ
        <code>date_facture</code> de type DATE dans la table FACTURE.
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