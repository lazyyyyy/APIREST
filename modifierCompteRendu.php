<?php
	require_once("fonctions.php");
	echo modifierCompteRendu($_POST["id_compte_rendu"], $_POST["date"], $_POST["bilan"], $_POST["coef_confiance"], $_POST["coef_notoriete"], $_POST["coef_prescription"], $_POST["id_motif"], $_POST["id_praticien"], $_POST["id_produit"], $_POST["nb_echantillons"]);
?>