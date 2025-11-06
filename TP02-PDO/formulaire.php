<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="assets/css/main.css"> -->
    <title>Test formulaire PDO</title>
</head>

<body class="bg-dark">
<header>
    <div class="container">
        <h1 class="text-light">Test formulaire PDO</h1>
    </div>
</header>
<main>
    <?php
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
    ?>
    <form action="formulaire.php" method="POST">
        <div class="form-group">
            <label for="nom" class="form-label text-light">Ton nom : </label>
            <input type="text" class="form-control" value="<?php if (isset($_POST['nom'])) {
                echo htmlentities($_POST['nom']);
            } ?>" id="nom" name="nom" aria-describedby="nomHelp">
            <?php
            if (isset($_POST['nom']) && empty($_POST['nom'])) {
                echo "<p style='color:white;'>le mail est vide </p>";
            }
            ?>
        </div>
        <div class="mb-3">
            <label for="mail" class="form-label text-light">Email address</label>
            <input type="email" class="form-control" value="<?php if (isset($_POST['mail'])) {
                echo htmlentities($_POST['mail']);
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
            <input type="text" class="form-control" value="<?php if (isset($_POST['sujet'])) {
                echo htmlentities($_POST['sujet']);
            } ?>" id="sujet" name="sujet" aria-describedby="nomHelp">
            <?php
            if (isset($_POST['sujet']) && empty($_POST['sujet'])) {
                echo "<p style='color:white;'>le sujet est vide </p>";
            }
            ?>
        </div>
        <div class="form-group">
            <label for="exampleFormControlTextarea1" class="form-label text-light">Ton texte : </label>
            <textarea class="form-control" name="message" value="<?php if (isset($_POST['message'])) {
                echo htmlentities($_POST['message']);
            } ?>" id="exampleFormControlTextarea1" rows="3"></textarea>
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
    if (isset($nom, $email, $sujet, $message)) {
        // Vérification des champs non vides
        if (!empty($nom) && !empty($email) && !empty($sujet) && !empty($message)) {

            // Affichage du résultat - Première occurrence
            echo "<h3 class='text-light'> Soumission du formulaire réussie :</h3>";
            echo "<p class='text-light'>Je m'appelle **$nom**, mon email est **$email**.</p> 
                  <p class='text-light'>Ma plainte porte sur **$sujet**.</p> 
                  <p class='text-light'>Contenu : <br> $message</p>";
        } else {
            // Optionnel : Afficher un message d'erreur si des champs sont vides
            echo "<h3 class='text-danger Create selector'>Erreur : Veuillez remplir tous les champs du formulaire.</h3>";
        }
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

<?php
