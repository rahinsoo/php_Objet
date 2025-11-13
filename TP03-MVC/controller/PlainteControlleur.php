<?php
require_once "./model/Plainte.php";
class PlainteControlleur
{
    private plaintes $plainte;

    /**
     * @param plaintes $plainte
     */
    public function __construct(plaintes $plainte)
    {
        $this->plainte = $plainte;
    }

    public function index()
    {
        $plaintes = $this->plainte->getAllPlaintes();
        include "./vue/liste_plainte.php";
    }

    //Afficher le formulaire d'ajout des plaintes
    public function addPlainte()
    {
        include "./vue/ajouter_plainte.php";
    }
//Afficher pour voir plaintes
    public function showPlainte($id)
    {
        $plainte = $this->plainte->showOnePlainte($id);
        include "./vue/show_plainte.php";
    }
}
