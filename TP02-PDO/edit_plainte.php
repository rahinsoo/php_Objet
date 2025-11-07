<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="assets/css/main.css"> -->
    <title>Formulaire Edition Plainte PDO</title>
</head>

<body class="bg-dark">
<header>
    <div class="container">
        <h1 class="text-light">Formulaire Edition Plainte PDO</h1>
    </div>
</header>
<main>
    <?php
    global $bdd;
    require_once "bdd.php";
    // Initialisation des variables
    $nom = $email = $sujet = $message = null;

    // Vérification de la soumission du formulaire et de la présence des champs
    if (isset($_POST['nom'], $_POST['mail'], $_POST['sujet'], $_POST['message'])) {

        // Récupération des données POST
        $nom = $_POST['nom'];
        $email = $_POST['mail'];
        $sujet = $_POST['sujet'];
        $message = $_POST['message'];
    }

    // récupère les infos via l'id
    if (isset($_GET['id']) && !empty($_GET['id'])){
        // SQL de récupération
        $id = $_GET['id'];
        $sql = "SELECT * FROM plainte WHERE id = :id";
        $query = $bdd->prepare($sql);
        $plainte = $query->execute([
            'id'=> $id
        ]);
    }else {
        echo "<h1> erreur </h1>";
    }
// récuperer
    $plainte = $query->fetch();
//    var_dump($plainte);

    ?>
    <a href="plainte.php">
        <button type="button" class="btn btn-secondary ">Voir les plaintes</button>
    </a>
    <form action="formulaire.php" method="POST">
        <!-- placer id de façon caché -->
        <div class="form-group">
            <label for="nom" class="form-label text-light">Ton nom : </label>
            <input type="text" class="form-control" value="<?php if (isset($plainte['nom'])) {
                echo htmlentities($plainte['nom']);
            } ?>" id="nom" name="nom" aria-describedby="nomHelp">
            <?php
            if (isset($_POST['nom']) && empty($_POST['nom'])) {
                echo "<p style='color:white;'>le mail est vide </p>";
            }
            ?>
        </div>
        <div class="mb-3">
            <label for="mail" class="form-label text-light">Email address</label>
            <input type="email" class="form-control" value="<?php if (isset($plainte['mail'])) {
                echo htmlentities($plainte['mail']);
            } ?>" id="mail" name="mail" aria-describedby="emailHelp">
            <div id="emailHelp" class="form-text text-light">We'll never share your email with anyone else.</div>
            <?php
            if (isset($_POST['mail']) && empty($_POST['mail'])) {
                echo "<p style='color:white;'>le nom est vide </p>";
            }
            ?>
        </div>
        <div class="mb-3">
            <label for="sujet" class="form-label text-light">Ton sujet : </label>
            <input type="text" class="form-control" value="<?php if (isset($plainte['sujet'])) {
                echo htmlentities($plainte['sujet']);
            } ?>" id="sujet" name="sujet" aria-describedby="nomHelp">
            <?php
            if (isset($_POST['sujet']) && empty($_POST['sujet'])) {
                echo "<p style='color:white;'>le sujet est vide </p>";
            }
            ?>
        </div>
        <div class="form-group">
            <label for="exampleFormControlTextarea1" class="form-label text-light">Ton texte : </label>
            <textarea class="form-control" name="message"  id="exampleFormControlTextarea1" rows="3"><?php if (isset($plainte['message'])) {
                    echo htmlentities($plainte['message']);
                } ?></textarea>
            <?php
            if (isset($_POST['message']) && empty($_POST['message'])) {
                echo "<p style='color:white;'>le message est vide </p>";
            }
            ?>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    <?php
    // Vérification de la soumission du formulaire et de la présence des champs
    if (isset($nom, $email, $sujet, $message, $id)) {
        // Vérification des champs non vides
        if (!empty($nom) && !empty($email) && !empty($sujet) && !empty($message) && !empty($id)) {
            // modification de la plainte
            $sql = "UPDATE Table SET nom= :nom, sujet= :sujet, message= :message WHERE id= :id";
//            $sql = "INSERT INTO plainte (nom, sujet, message, date_plainte) VALUE (:nom, :sujet, :message, :date_plainte)";
                header("Location: plainte.php?success=1");
                exit();
            }
        } else {
            // Optionnel : Afficher un message d'erreur si des champs sont vides
            echo "<h3 class='text-danger Create selector'>Erreur : Veuillez remplir tous les champs du formulaire.</h3>";
        }
    ?>
</main>
<footer>
    <div class="container-fluid">
        <div class="row text-center text-bg-dark">
            <div class="col">Créé par Xavier en 2025</div>
        </div>
    </div>
</footer>
</body>

</html>
