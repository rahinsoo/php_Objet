<?php
global $bdd;
require_once "config/db.php";

// Récupération de la liste des clients pour le filtre
try {
    $sql = "SELECT id_client, nom, prenom FROM CLIENTS ORDER BY nom, prenom";
    $stmt = $bdd->query($sql);
    $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(Exception $e) {
    die("Erreur lors de la récupération des clients : " . $e->getMessage());
}

$factures = [];
$searched = false;

// Initialisation des variables de filtre avec les valeurs POST si elles existent
$date_debut = $_POST['date_debut'] ?? '';
$date_fin = $_POST['date_fin'] ?? '';
$id_client = $_POST['id_client'] ?? '';

// Traitement de l'affichage (recherche ou toutes les factures)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Si le formulaire est soumis, on effectue une recherche
    $searched = true;
}

// Construction de la requête SQL dynamique
$sql = "SELECT f.*, c.nom, c.prenom 
        FROM FACTURE f
        INNER JOIN CLIENTS c ON f.id_client = c.id_client
        WHERE 1=1";

$params = [];

if ($searched) {
    // Filtre par client
    if (!empty($id_client)) {
        $sql .= " AND f.id_client = :id_client";
        $params['id_client'] = $id_client;
    }

    // Filtre par date de début (date_facture >= date_debut)
    if (!empty($date_debut)) {
        $sql .= " AND f.date_facture >= :date_debut";
        $params['date_debut'] = $date_debut;
    }

    // Filtre par date de fin (date_facture <= date_fin)
    if (!empty($date_fin)) {
        $sql .= " AND f.date_facture <= :date_fin";
        $params['date_fin'] = $date_fin;
    }
}

$sql .= " ORDER BY f.id_facture DESC";

try {
    $stmt = $bdd->prepare($sql);
    $stmt->execute($params);
    $factures = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(Exception $e) {
    if (strpos($e->getMessage(), 'date_facture') !== false) {
        die("Erreur lors de la récupération des factures : La colonne 'date_facture' n'existe pas dans la table FACTURE. Veuillez l'ajouter.");
    } else {
        die("Erreur lors de la récupération des factures : " . $e->getMessage());
    }
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
        <h1 class="text-light">Liste des Factures 🧾</h1>
    </div>
</header>
<main class="container mt-4">

    <div class="mb-3">
        <a href="index.php" class="btn btn-light">Gestion des factures</a>
        <a href="add_client.php" class="btn btn-success">Ajouter un client</a>
        <a href="list_clients.php" class="btn btn-secondary">Voir les clients</a>
        <a href="add_facture.php" class="btn btn-info">Créer une facture</a>
    </div>

    <div class="card bg-secondary mb-4">
        <div class="card-body">
            <h5 class="card-title text-light">Filtres de recherche</h5>
            <form action="list_factures.php" method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="id_client" class="form-label text-light">Client :</label>
                        <select class="form-control" id="id_client" name="id_client">
                            <option value="">-- Tous les clients --</option>
                            <?php foreach($clients as $client): ?>
                                <option value="<?php echo $client['id_client']; ?>"
                                        <?php echo ($id_client == $client['id_client']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($client['nom'] . ' ' . $client['prenom']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="date_debut" class="form-label text-light">Date début :</label>
                        <input type="date" class="form-control" id="date_debut" name="date_debut"
                               value="<?php echo htmlspecialchars($date_debut); ?>">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="date_fin" class="form-label text-light">Date fin :</label>
                        <input type="date" class="form-control" id="date_fin" name="date_fin"
                               value="<?php echo htmlspecialchars($date_fin); ?>">
                    </div>
                </div>

                <button type="submit" class="btn btn-warning">Rechercher</button>
                <?php if ($searched): ?>
                    <a href="list_factures.php" class="btn btn-secondary">Afficher tout</a>
                <?php endif; ?>
            </form>
        </div>
    </div>
    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-success">Facture supprimée avec succès !</div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">Erreur lors de la suppression de la facture.</div>
    <?php endif; ?>

    <?php if (empty($factures)): ?>
        <div class="alert alert-warning">
            <?php echo $searched ? "Aucune facture ne correspond à vos critères de recherche." : "Aucune facture enregistrée."; ?>
            <a href="add_facture.php" class="alert-link">Créer une facture</a>
        </div>
    <?php else: ?>
        <h2 class="text-light"><?php echo $searched ? "Résultats de la recherche" : "Toutes les factures"; ?></h2>
        <div class="table-responsive">
            <table class="table table-striped table-dark">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Produits</th>
                    <th>Quantité</th>
                    <th>date facture</th>
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
                        <td><?php echo date('d-m-Y', strtotime($facture['date_facture'])); ?></td>
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
                    <td colspan="5" class="text-end"><strong>Total :</strong></td>
                    <td colspan="2"><strong><?php echo number_format($total, 2, ',', ' '); ?> €</strong></td>
                </tr>
                </tfoot>
            </table>
        </div>
        <p class="text-light"><?php echo $searched ? "Résultats" : "Total"; ?> : <?php echo count($factures); ?> facture(s)</p>
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