<?php
$nom = $_POST['nom'];
$email = $_POST['mail'];
$sujet = $_POST['sujet'];
$message = $_POST['message'];

if ( isset($nom) && isset($email) && isset($sujet) && isset($message)){
    if (empty($nom)){
        echo "le nom est vide <br>";
    }
    if (empty($email)){
        echo "l'email est vide <br>";
    }
    if (empty($sujet)){
        echo "le sujet est vide <br>";
    }
    if (empty($message)){
        echo "le message est vide <br>";
    }
    if (!empty($nom) && !empty($email) && !empty($sujet) && !empty($message)){
        echo "je m'appelle $nom nom email est $email . Ma plainte porte sur $sujet <br> contenu : <br> $message";
    }

}
