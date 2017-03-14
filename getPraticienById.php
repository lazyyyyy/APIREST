<?php
	/*function getPraticienById($id)
	{
		include("connexionBdd.php");
		
		$i = 0;
		$praticiens = null;
		
		$req = $bdd->prepare("SELECT * FROM praticien WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$praticiens["id"] = $data["id"];
			$praticiens["nom"] = $data["nom"];
			$praticiens["prenom"] = $data["prenom"];
			$praticiens["telephone_fixe"] = $data["telephone_fixe"];
			$praticiens["telephone_portable"] = $data["telephone_portable"];
			$praticiens["mail"] = $data["mail"];
			$praticiens["date_derniere_visite"] = $data["date_derniere_visite"];
			
			require_once("fonctions.php");
			$praticiens["fonction"] = json_decode(getFonctionPraticienById($data["id_fonction_praticien"]));
			
			$praticiens["type_praticien"] = json_decode(getTypePraticienById($data["id_type_praticien"]));
			
			$praticiens["specialite"] = json_decode(getSpecialitePraticienById($data["id_specialite"]));
			
			$praticiens["lieu"] = json_decode(getLieuById($data["id_lieu"]));
			
			$i++;
		}
		return json_encode($praticiens);
	}*/
	include("fonctions.php");
	
	echo getPraticienById($_POST["id_praticien"]);
?>