<?php
global $bdd;
require_once "config/db.php";

// Vérification de la présence de l'ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: list_factures.php");
    exit();
}

$id_facture = $_GET['id'];

// Récupération de la facture
try {
    $sql = "SELECT * FROM FACTURE WHERE id_facture = :id";
    $stmt = $bdd->prepare($sql);
    $stmt->execute(['id' => $id_facture]);
    $facture = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$facture) {
        header("Location: list_factures.php");
        exit();
    }
} catch (Exception $e) {
    die("Erreur : " . $e->getMessage());
}

// Récupération de la liste des clients
try {
    $sql = "SELECT id_client, nom, prenom FROM CLIENTS ORDER BY nom, prenom";
    $stmt = $bdd->query($sql);
    $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Erreur : " . $e->getMessage());
}

$errors = [];
$success = false;

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

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

    // Si pas d'erreurs, mise à jour
    if (empty($errors)) {
        try {
            $sql = "UPDATE FACTURE SET montant = :montant, produits = :produits, quantite = :quantite, id_client = :id_client WHERE id_facture = :id";
            $update = $bdd->prepare($sql);
            $verif = $update->execute([
                    'montant' => $montant,
                    'produits' => $produits,
                    'quantite' => $quantite,
                    'id_client' => $id_client,
                    'id' => $id_facture
            ]);

            if ($verif) {
                $success = true;
                // Recharger les données de la facture
                $facture['montant'] = $montant;
                $facture['produits'] = $produits;
                $facture['quantite'] = $quantite;
                $facture['id_client'] = $id_client;
            }
        } catch (Exception $e) {
            $errors[] = "Erreur lors de la mise à jour : " . $e->getMessage();
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
    <title>Modifier une Facture</title>
</head>
<body class="bg-dark">
<header class="py-3">
    <div class="container">
        <h1 class="text-light">Modifier la Facture #<?php echo htmlspecialchars($id_facture); ?></h1>
    </div>
</header>
<main class="container mt-4">

    <div class="mb-3">
        <a href="index.php" class="btn btn-light">Gestion des factures</a>
        <a href="list_factures.php" class="btn btn-secondary">Retour à la liste</a>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success">Facture modifiée avec succès !</div>
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
            <form action="edit_facture.php?id=<?php echo $id_facture; ?>" method="POST">
                <div class="mb-3">
                    <label for="id_client" class="form-label text-light">Client :</label>
                    <select class="form-control" id="id_client" name="id_client" required>
                        <option value="">-- Sélectionner un client --</option>
                        <?php foreach ($clients as $client): ?>
                            <option value="<?php echo $client['id_client']; ?>"
                                    <?php echo ($facture['id_client'] == $client['id_client']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($client['nom'] . ' ' . $client['prenom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="produits" class="form-label text-light">Produits (description) :</label>
                    <textarea class="form-control" id="produits" name="produits" rows="3"
                              required><?php echo htmlspecialchars($facture['produits']); ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="quantite" class="form-label text-light">Quantité :</label>
                    <input type="number" class="form-control" id="quantite" name="quantite"
                           value="<?php echo htmlspecialchars($facture['quantite']); ?>" min="1" required>
                </div>

                <div class="mb-3">
                    <label for="montant" class="form-label text-light">Montant (€) :</label>
                    <input type="number" class="form-control" id="montant" name="montant"
                           value="<?php echo htmlspecialchars($facture['montant']); ?>"
                           step="0.01" min="0.01" required>
                </div>

                <button type="submit" class="btn btn-primary">Modifier la facture</button>
                <a href="list_factures.php" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
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