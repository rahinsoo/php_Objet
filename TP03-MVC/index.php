<p class="text-light">Hello World --> index.php</p>
<?php
require_once "controller/PlainteControlleur.php";
require_once "model/Plainte.php";
require_once "model/Database.php";

$bdd = new Database();
$plainteModel = new Plaintes($bdd);
$plainteControlleur = new PlainteControlleur($plainteModel);

if (isset($_GET['page'])) {
    switch ($_GET['page']) {
        case 'add_plainte' :
            $plainteControlleur->addPlainte();
            break;
        case 'show_plainte':
            $id = $_GET['id'];
            $plainteControlleur->showPlainte($id);
            break;
        default:
            $plainteControlleur->index();
            break;
    }
} else {
    $plainteControlleur->index();
}
