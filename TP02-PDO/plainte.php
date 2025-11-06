<?php
global $bdd;
require_once "bdd.php";

$sql = "SELECT * from plainte";
$query = $bdd->query($sql);
$plainte = $query->fetchAll();

// supprimer une entité :
if(isset($_GET['id']) && !empty($_GET['id'])){
// réccupération de mon id
    $id = $_GET['id'];
    $sql = "DELETE FROM plainte WHERE id = :id";
    $query = $bdd->prepare($sql);
    $verif = $query-> execute([
            'id'=> $id
    ]);
    if ($verif){
        header("Location: plainte.php");
//        exit();
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
        <title>Test formulaire PDO</title>
    </head>

    <body class="bg-dark">
    <header>
        <div class="container text-center">
            <h1 class="text-light">FOOOOrmuullaiiiire PDO de Plaaaaaaiiintes</h1>
        </div>
    </header>
    <main>
        <a href="formulaire.php">
            <button type="button" class="btn btn-secondary ">FOOORRRRMMMUUUULLLLAAAIIREEEEE</button>
        </a>

        <table class="table">
            <thead>
            <tr>
                <th scope="col">id</th>
                <th scope="col">nom</th>
                <th scope="col">sujet</th>
                <th scope="col">message</th>
                <th scope="col">date plainte</th>
                <th scope="col">status</th>
                <th scope="col">action</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($plainte as $item) {?>
                <tr>
                    <th scope="col"> <?php echo $item['id']?> </th>
                    <th scope="col"> <?php echo $item['nom']?> </th>
                    <th scope="col"> <?php echo $item['sujet']?> </th>
                    <th scope="col"> <?php echo $item['message']?> </th>
                    <th scope="col"> <?php echo $item['date_plainte']?> </th>
                    <th scope="col">
                            <?php echo ($item['visible'] == 1) ? "<span class='btn btn-success'> visible</span>" : "<span class='btn btn-danger'> invisible</span>"; ?>
                    </th>
                    <th scope="col">
                        <a href="plainte.php?id=<?php echo $item['id']; ?>"><span class='btn btn-danger'>Supprimer</span></a>
                        <a ><span class='btn btn-warning'>Modifier</span></a>
                    </th>
                </tr>
            <?php }
            ?>


            </tbody>
        </table>
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

<?php

