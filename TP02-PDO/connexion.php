<?php
global $bdd;
require_once "bdd.php";


// empecher l'user connecté  d'avoir accès à la page de connection
//if (isset($_COOKIE["uviser_connected"]) && !empty($_COOKIE["user_connected"])) {
//    header("Location: plainte.php");
//}

//Traitement de la page de connexion
if (isset($_POST['mail']) && isset($_POST['password'])) {
    $email = $_POST['mail'];
    $password = $_POST['password'];

    if (!empty($email) && !empty($password) && $email == "test@test" && $password == "0000") {
        /**
         * verifier dans la bdd si le mail et le password est confirmé à une même ligne
         */
        $sql = "SELECT * FROM admin WHERE email = :email and password = :password";
        $query = $bdd->prepare($sql);
        $admin = $query->execute([
                'email'=> $email,
                'password'=> $password
        ]);

        $admin = $query->fetch();

        if (!empty($admin)) {
            setcookie("user_connected", $email, time()+3600);
            setcookie("user_infos", $admin['nom'] . " " . $admin['prenom'], time()+3600);

            // création de la _SESSION
            session_start();
            $_SESSION['admin'] = $admin;
            $_SESSION['welcome_msg'] = "Bienvenue maitre ".$admin['nom'];

            //une fois le cookie créé je le redirige sur la page d'accueil (liste des plaintes)
            header('Location: plainte.php');
            //ok la personne est connecté
        }

    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="assets/css/main.css"> -->
    <title>PAGE DE CONNEXION PDO</title>
</head>

<body class="bg-dark">
<header>
    <div class="container text-center">
        <h1 class="text-light">PAAAGGGGGEEEE de CCOOOOONNNNNNEEEEEEXIIIIOOOOONNN PDO</h1>
    </div>
</header>
<main>
    <a href="plainte.php">
        <button type="button" class="btn btn-secondary ">retour sur les plaintes</button>
    </a>
    <form action="connexion.php" method="POST">

        <div class="mb-3">
            <label for="mail" class="form-label text-light">Email address</label>
            <input type="email" class="form-control" value="<?php if (isset($_POST['mail'])) {
                echo htmlentities($_POST['mail']);
            } ?>" id="mail" name="mail" aria-describedby="emailHelp">
            <div id="emailHelp" class="form-text text-light">We'll never share your email with anyone else.</div>
            <?php
            if (isset($_POST['mail']) && empty($_POST['mail'])) {
                echo "<p style='color:white;'>le mail est vide </p>";
            }
            ?>
        </div>
        <div class="form-group">
            <label for="password" class="form-label text-light">PWD : </label>
            <input type="password" class="form-control" value="<?php if (isset($_POST['password'])) {
                echo htmlentities($_POST['password']);
            } ?>" id="mdp" name="password" aria-describedby="mdpHelp">
            <?php
            if (isset($_POST['password']) && empty($_POST['password'])) {
                echo "<p style='color:white;'>le pwd est vide </p>";
            }
            ?>
        </div>
        <br>
        <button type="submit" class="btn btn-danger">Se connecter</button>
    </form>
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
