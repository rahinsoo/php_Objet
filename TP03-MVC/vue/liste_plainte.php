<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="assets/css/main.css"> -->
    <title>Test formulaire MVC</title>
</head>

<body class="bg-dark">
<header>
    <div class="container text-center">
        <h1 class="text-light">Liste plainte MVC</h1>
    </div>
</header>
<main>
    <div class="d-flex">
        <div>
            <a href="./index.php?page=add_plainte">
                <button type="button" class="btn btn-outline-secondary ">Ajoutez plainte</button>
            </a>
        </div>
        <?php if (isset($_COOKIE["user_connected"]) && !empty($_COOKIE["user_connected"])) { ?>
            <div>
                <a href="plainte.php?action=logout" class="btn btn-outline-danger ">se déconnecter
                </a>
            </div>
            <div>
                <a href="profil_file.php" class="btn btn-outline-danger ">mon compte
                </a>
            </div>
        <?php } else { ?>
            <div>
                <a href="connexion.php" class="btn btn-outline-secondary ">COOONNNNECTION
                </a>
            </div>
        <?php } ?>
    </div>


    <div class="p-2">
        <table class="table table-striped table-hover table-dark">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nom</th>
                <th scope="col">sujet</th>
                <th scope="col">message</th>
                <th scope="col">date plainte</th>
                <th scope="col">status</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($plaintes as $plainte) {
                ?>
                <tr>
                    <td scope="row"><?php echo $plainte['id']; ?></td>
                    <td scope="row"><?php echo $plainte['nom']; ?></td>
                    <td scope="col"> <?php echo $plainte['sujet']; ?> </td>
                    <td scope="col"> <?php echo $plainte['message']; ?> </td>
                    <td scope="col"> <?php echo $plainte['date_plainte']; ?> </td>
                    <td scope="col">
                        <?php echo ($plainte['visible'] == 1) ? "<span class='badge bg-success'> visible</span>" : "<span class='badge bg-danger'> invisible</span>"; ?>
                    </td>
                    <td scope="col">
                        <a href="./index.php?page=show_plainte&id=<?php echo $plainte['id']; ?>">view</a>
                    </td>
                </tr>
            <?php } ?>

            </tbody>
        </table>
    </div>
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