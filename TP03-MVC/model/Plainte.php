<?php
require_once "Database.php";
class Plaintes
{
    // les attributs
    private $id;
    private $nom;
    private $sujet;
    private $message;
    private $date_plainte;

    private Database $bdd;

    // constructeur de plainte
    public function __construct(Database $bdd)
    {
        $this->bdd = $bdd;
    }

    // constructeur, getteur et setteurs
    function getAllPlaintes()
    {
        $sql = "SELECT * from plainte";
        $query = $this->bdd->getBdd()->query($sql);
        return $plainte = $query->fetchAll();
    }

    // réccupérer une plainte

    public function showOnePlainte($id)
    {
        $sql = "SELECT * from plainte WHERE id= :id";
        $query = $this->bdd->getBdd()->query($sql);
        $plainte = $query->execute([
            'id'=> $id
        ]);
        return $plainte = $query->fetch();
    }
}