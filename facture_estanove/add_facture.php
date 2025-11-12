<?php
global $bdd;
require_once "config/db.php";

// Récupération de la liste des clients pour le menu déroulant
try {
    $sql = "SELECT id_client, nom, prenom FROM CLIENTS ORDER BY nom, prenom";
    $stmt = $bdd->query($sql);
    $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Erreur lors de la récupération des clients : " . $e->getMessage());
}

// Initialisation des variables
$montant = $produits = $quantite = $id_client = null;
$errors = [];
$success = false;

// Vérification de la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Récupération et validation des données
    $montant = trim($_POST['montant'] ?? '');
    $produits = trim($_POST['produits'] ?? '');
    $quantite = trim($_POST['quantite'] ?? '');
    $id_client = $_POST['id_client'] ?? '';

    // Validation
    if (empty($montant) || !is_numeric($montant) || $montant <= 0) {
        $errors[] = "Le montant doit être un nombre positif";
    }
    if (empty($produits)) {
        $errors[] = "La description des produits est obligatoire";
    }
    if (empty($quantite) || !is_numeric($quantite) || $quantite <= 0) {
        $errors[] = "La quantité doit être un nombre entier positif";
    }
    if (empty($id_client)) {
        $errors[] = "Veuillez sélectionner un client";
    }

    // Si pas d'erreurs, insertion en base
    if (empty($errors)) {
        try {
            $sql = "INSERT INTO FACTURE (montant, produits, quantite, id_client) VALUES (:montant, :produits, :quantite, :id_client)";
            $insert = $bdd->prepare($sql);
            $verif = $insert->execute([
                'montant' => $montant,
                'produits' => $produits,
                'quantite' => $quantite,
                'id_client' => $id_client
            ]);

            if ($verif) {
                $success = true;
                // Réinitialiser les champs
                $montant = $produits = $quantite = $id_client = '';
            }
        } catch (Exception $e) {
            $errors[] = "Erreur lors de l'insertion : " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Créer une Facture</title>
</head>
<body class="bg-dark">
<header class="py-3">
    <div class="container">
        <h1 class="text-light">Créer une Facture</h1>
    </div>
</header>
<main class="container mt-4">

    <div class="mb-3">
        <a href="index.php" class="btn btn-light">Gestion des factures</a>
        <a href="add_client.php" class="btn btn-success">Ajouter un client</a>
        <a href="list_clients.php" class="btn btn-secondary">Voir les clients</a>
        <a href="list_factures.php" class="btn btn-primary">Voir les factures</a>
    </div>

    <?php if (empty($clients)): ?>
        <div class="alert alert-warning">
            Aucun client disponible. Vous devez d'abord <a href="add_client.php" class="alert-link">ajouter un
                client</a> avant de créer une facture.
        </div>
    <?php else: ?>

        <?php if ($success): ?>
            <div class="alert alert-success">Facture créée avec succès !</div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="card bg-secondary">
            <div class="card-body">
                <form action="add_facture.php" method="POST">
                    <div class="mb-3">
                        <label for="id_client" class="form-label text-light">Client :</label>
                        <select class="form-control" id="id_client" name="id_client" required>
                            <option value="">-- Sélectionner un client --</option>
                            <?php foreach ($clients as $client): ?>
                                <option value="<?php echo $client['id_client']; ?>"
                                    <?php echo ($id_client == $client['id_client']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($client['nom'] . ' ' . $client['prenom']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="produits" class="form-label text-light">Produits (description) :</label>
                        <textarea class="form-control" id="produits" name="produits" rows="3"
                                  required><?php echo htmlspecialchars($produits ?? ''); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="quantite" class="form-label text-light">Quantité :</label>
                        <input type="number" class="form-control" id="quantite" name="quantite"
                               value="<?php echo htmlspecialchars($quantite ?? ''); ?>" min="1" required>
                    </div>

                    <div class="mb-3">
                        <label for="montant" class="form-label text-light">Montant (€) :</label>
                        <input type="number" class="form-control" id="montant" name="montant"
                               value="<?php echo htmlspecialchars($montant ?? ''); ?>"
                               step="0.01" min="0.01" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Créer la facture</button>
                </form>
            </div>
        </div>
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