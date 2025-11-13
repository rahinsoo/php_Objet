<?php
global $bdd;
require_once "bdd.php";

// ouvrir la session --> qui permet de réccupérer les données de la page connexion.php
session_start();

// se déconnecter
if (isset($_COOKIE["user_connected"]) && !empty($_COOKIE["user_connected"]) && isset($_GET['action']) == "logout") {
    // je détruit mon cookie
    setcookie("user_connected", null, time() - 3600);
    session_destroy();
    header("Location: plainte.php");
}


// Supprimer une entité
if (isset($_GET['id']) && !empty($_GET['id']) && isset($_GET['action']) && $_GET['action'] == 'supprimer') {
    $id = $_GET['id'];
    $sql = "DELETE FROM plainte WHERE id = :id";
    $query = $bdd->prepare($sql);
    $verif = $query->execute(['id' => $id]);

    if ($verif) {
        header("Location: plainte.php");
        exit();
    }
}

// Gérer la visibilité (SÉPARÉ, pas imbriqué !)
if (isset($_GET['id']) && !empty($_GET['id']) && isset($_GET['visible']) && isset($_GET['action']) && $_GET['action'] == 'gerervisibilite') {
    $visible = $_GET['visible'];
    $id = $_GET['id'];

    // Inverser : si visible=1 alors mettre 0, si visible=0 alors mettre 1
    $nouveauStatut = ($visible == 1) ? 0 : 1;

    $sql = "UPDATE plainte SET visible = :visible WHERE id = :id";
    $update = $bdd->prepare($sql);
    $verif = $update->execute([
        'id' => $id,
        'visible' => $nouveauStatut,
    ]);

    if ($verif) {
        header("Location: plainte.php");
        exit();
    }
}
if (isset($_GET['id_selected'])) {
    $id_selected = $_GET['id_selected'];
    // vérifiez si la table n'est pas vide
    if (count($id_selected) > 0) {
        $sql = "DELETE FROM plainte WHERE id = :id";
        $query = $bdd->prepare($sql);

// Suppression des id
        foreach ($id_selected as $id_s) {
            $query->execute([
                'id' => $id_s // valeur actuelle
            ]);
        }
    }
}
// Récupérer toutes les plaintes
$sql = "SELECT * from plainte";
$query = $bdd->query($sql);
$plainte = $query->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="assets/css/main.css"> -->
    <title>page de connection PDO</title>
</head>

<body class="bg-dark">
<header>
    <div class="container text-center">
        <h1 class="text-light">PAAAAAGGEEEEE DE COOOONNNEEEEXXIIOOONN PDO</h1>
        <?php
        //affichage connecté en admin
        if (isset($_SESSION['welcome_msg']) && !empty($_SESSION['welcome_msg'])) {
            echo "<h3 class='text-secondary'>" . $_SESSION['welcome_msg'] . "</h3>";
        }
        ?>
    </div>
</header>
<main>
    <section>
        <div class="d-flex justify-content-between">
            <div class="p-2">
                <a href="plainte.php" class="btn btn-secondary "> Voir les plaintes
                </a>
            </div>
            <?php if (isset($_COOKIE["user_connected"]) && !empty($_COOKIE["user_connected"])) { ?>
                <div class="p-2">
                    <a href="plainte.php?action=logout" class="btn btn-outline-danger ">se déconnecter
                    </a>
                </div>
            <?php } else { ?>
                <div class="p-2">
                    <a href="connexion.php" class="btn btn-outline-secondary ">COOONNNNECTION
                    </a>
                </div>
            <?php } ?>
        </div>
    </section>
    <section class="p-2">
        <div class="card text-center alert alert-success">
            <div class="card-header">
                <?php
                //affichage connecté en admin
                if (isset($_SESSION['welcome_msg']) && !empty($_SESSION['welcome_msg'])) {
                    echo "<h3 class='text-secondary'>" . $_SESSION['welcome_msg'] . "</h3>";
                }
                ?>
            </div>
            <div class="card-body">
                <h5 class="card-title">Voici mes infos</h5>
                <p class="card-text">Tout ce que je peux écrire n'a pas de sens, mais l'idée est de tester, de faire des erreurs et d'apprendre grâce à celles-ci.</p>
                <a href="#" class="btn btn-outline-info">Voir mes plaintes</a>
            </div>
            <div class="card-footer text-muted">
                Je suis mon propre maitre \(*.*)/
            </div>
        </div>
    </section>



</main>
<footer>
    <div class="container-fluid">
        <div class="row text-center text-bg-dark">
            <div class="col">Créé par Rah Insoo en 2025</div>
        </div>
    </div>
</footer>
</body>
</html>
