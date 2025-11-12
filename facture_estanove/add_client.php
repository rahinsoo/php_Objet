<?php
global $bdd;
require_once "config/db.php";

// Initialisation des variables
$nom = $prenom = $sexe = $date_naissance = null;
$errors = [];
$success = false;

// Vérification de la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Récupération et validation des données
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $sexe = $_POST['sexe'] ?? '';
    $date_naissance = $_POST['date_naissance'] ?? '';
    
    // Validation
    if (empty($nom)) {
        $errors[] = "Le nom est obligatoire";
    }
    if (empty($prenom)) {
        $errors[] = "Le prénom est obligatoire";
    }
    if (empty($sexe) || !in_array($sexe, ['H', 'F'])) {
        $errors[] = "Le sexe doit être H ou F";
    }
    if (empty($date_naissance)) {
        $errors[] = "La date de naissance est obligatoire";
    }
    
    // Si pas d'erreurs, insertion en base
    if (empty($errors)) {
        try {
            $sql = "INSERT INTO CLIENTS (nom, prenom, sexe, date_naissance) VALUES (:nom, :prenom, :sexe, :date_naissance)";
            $insert = $bdd->prepare($sql);
            $verif = $insert->execute([
                'nom' => $nom,
                'prenom' => $prenom,
                'sexe' => $sexe,
                'date_naissance' => $date_naissance
            ]);
            
            if ($verif) {
                $success = true;
                // Réinitialiser les champs
                $nom = $prenom = $sexe = $date_naissance = '';
            }
        } catch(Exception $e) {
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
    <title>Ajouter un Client</title>
</head>
<body class="bg-dark">
<header class="py-3">
    <div class="container">
        <h1 class="text-light">Ajouter un Client</h1>
    </div>
</header>
<main class="container mt-4">
    
    <div class="mb-3">
        <a href="index.php" class="btn btn-light">Gestion des factures</a>
        <a href="list_clients.php" class="btn btn-secondary">Voir la liste des clients</a>
        <a href="add_facture.php" class="btn btn-info">Créer une facture</a>
        <a href="list_factures.php" class="btn btn-primary">Voir les factures</a>
    </div>
    
    <?php if ($success): ?>
        <div class="alert alert-success">Client ajouté avec succès !</div>
    <?php endif; ?>
    
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <div class="card bg-secondary">
        <div class="card-body">
            <form action="add_client.php" method="POST">
                <div class="mb-3">
                    <label for="nom" class="form-label text-light">Nom :</label>
                    <input type="text" class="form-control" id="nom" name="nom" 
                           value="<?php echo htmlspecialchars($nom ?? ''); ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="prenom" class="form-label text-light">Prénom :</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" 
                           value="<?php echo htmlspecialchars($prenom ?? ''); ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="sexe" class="form-label text-light">Sexe :</label>
                    <select class="form-control" id="sexe" name="sexe" required>
                        <option value="">-- Choisir --</option>
                        <option value="H" <?php echo ($sexe === 'H') ? 'selected' : ''; ?>>Homme</option>
                        <option value="F" <?php echo ($sexe === 'F') ? 'selected' : ''; ?>>Femme</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="date_naissance" class="form-label text-light">Date de naissance :</label>
                    <input type="date" class="form-control" id="date_naissance" name="date_naissance" 
                           value="<?php echo htmlspecialchars($date_naissance ?? ''); ?>" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Ajouter le client</button>
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