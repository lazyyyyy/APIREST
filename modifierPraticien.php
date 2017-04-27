<?php
require_once("fonctions.php");

echo modifierPraticien($_POST["id"], $_POST["nom"], $_POST["prenom"], $_POST["telFixe"], $_POST["telPortable"], $_POST["mail"], $_POST["typePraticien"], $_POST["specialite"], $_POST["idLieu"], $_POST["adresseLieu"], $_POST["cpLieu"], $_POST["villeLieu"], $_POST["paysLieu"], $_POST["region"], $_POST["dateDerniereVisiste"]);
?>