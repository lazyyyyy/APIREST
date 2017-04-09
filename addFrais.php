<?php
require_once("fonctions.php");
echo addFrais($_POST["montant"], $_POST["commentaire"], $_POST["date"], $_POST["id_utilisateur"], $_POST["id_type_frais"]);
?>