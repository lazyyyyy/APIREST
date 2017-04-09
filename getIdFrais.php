<?php
require_once("fonctions.php");
echo getIdFrais($_POST["montant"], $_POST["commentaire"], $_POST["date"], $_POST["id_utilisateur"], $_POST["id_type_frais"]);
?>