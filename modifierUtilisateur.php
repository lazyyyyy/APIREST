<?php
	require_once("fonctions.php");
	
	echo modifierUtilisateur($_POST["id"], $_POST["nom"], $_POST["prenom"], $_POST["date_naissance"], $_POST["date_embauche"], $_POST["telephone_fixe"], $_POST["telephone_portable"], $_POST["mail"], $_POST["id_fonction_utilisateur"], $_POST["id_laboratoire"]);
?>