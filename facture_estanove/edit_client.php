<?php
global $bdd;
require_once "config/db.php";

// Vérification de la présence de l'ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: list_clients.php");
    exit();
}

$id_client = $_GET['id'];

// Récupération du client
try {
    $sql = "SELECT * FROM CLIENTS WHERE id_client = :id";
    $stmt = $bdd->prepare($sql);
    $stmt->execute(['id' => $id_client]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$client) {
        header("Location: list_clients.php");
        exit();
    }
} catch(Exception $e) {
    die("Erreur : " . $e->getMessage());
}

$errors = [];
$success = false;

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
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
    
    // Si pas d'erreurs, mise à jour
    if (empty($errors)) {
        try {
            $sql = "UPDATE CLIENTS SET nom = :nom, prenom = :prenom, sexe = :sexe, date_naissance = :date_naissance WHERE id_client = :id";
            $update = $bdd->prepare($sql);
            $verif = $update->execute([
                'nom' => $nom,
                'prenom' => $prenom,
                'sexe' => $sexe,
                'date_naissance' => $date_naissance,
                'id' => $id_client
            ]);
            
            if ($verif) {
                $success = true;
                // Recharger les données du client
                $client['nom'] = $nom;
                $client['prenom'] = $prenom;
                $client['sexe'] = $sexe;
                $client['date_naissance'] = $date_naissance;
            }
        } catch(Exception $e) {
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
    <title>Modifier un Client</title>
</head>
<body class="bg-dark">
<header class="py-3">
    <div class="container">
        <h1 class="text-light">Modifier le Client #<?php echo htmlspecialchars($id_client); ?></h1>
    </div>
</header>
<main class="container mt-4">
    
    <div class="mb-3">
        <a href="list_clients.php" class="btn btn-secondary">Retour à la liste</a>
    </div>
    
    <?php if ($success): ?>
        <div class="alert alert-success">Client modifié avec succès !</div>
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
            <form action="edit_client.php?id=<?php echo $id_client; ?>" method="POST">
                <div class="mb-3">
                    <label for="nom" class="form-label text-light">Nom :</label>
                    <input type="text" class="form-control" id="nom" name="nom" 
                           value="<?php echo htmlspecialchars($client['nom']); ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="prenom" class="form-label text-light">Prénom :</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" 
                           value="<?php echo htmlspecialchars($client['prenom']); ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="sexe" class="form-label text-light">Sexe :</label>
                    <select class="form-control" id="sexe" name="sexe" required>
                        <option value="">-- Choisir --</option>
                        <option value="H" <?php echo ($client['sexe'] === 'H') ? 'selected' : ''; ?>>Homme</option>
                        <option value="F" <?php echo ($client['sexe'] === 'F') ? 'selected' : ''; ?>>Femme</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="date_naissance" class="form-label text-light">Date de naissance :</label>
                    <input type="date" class="form-control" id="date_naissance" name="date_naissance" 
                           value="<?php echo htmlspecialchars($client['date_naissance']); ?>" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Modifier le client</button>
                <a href="list_clients.php" class="btn btn-secondary">Annuler</a>
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