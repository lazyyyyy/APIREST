<?php
	function getPraticienByName($nom)
	{
		include("connexionBdd.php");
		
		$nom = "%".strtoupper($nom)."%";
		$i = 0;
		$praticiens = null;
		
		$req = $bdd->prepare("SELECT * FROM praticien WHERE UCASE(nom) LIKE ? ORDER BY nom");
		$req->execute(array($nom));
		while($data = $req->fetch())
		{
			$praticiens[$i]["id"] = $data["id"];
			$praticiens[$i]["nom"] = $data["nom"];
			$praticiens[$i]["prenom"] = $data["prenom"];
			$praticiens[$i]["telephone_fixe"] = $data["telephone_fixe"];
			$praticiens[$i]["telephone_portable"] = $data["telephone_portable"];
			$praticiens[$i]["mail"] = $data["mail"];
			$praticiens[$i]["date_derniere_visite"] = $data["date_derniere_visite"];
			
			require_once("getFonctionPraticienById.php");
			$praticiens[$i]["fonction"] = json_decode(getFonctionPraticienById($data["id_fonction_praticien"]));
			
			require_once("getTypePraticienById.php");
			$praticiens[$i]["type_praticien"] = json_decode(getTypePraticienById($data["id_type_praticien"]));
			
			require_once("getSpecialitePraticienById.php");
			$praticiens[$i]["specialite"] = json_decode(getSpecialitePraticienById($data["id_specialite"]));
			
			require_once("getLieuById.php");
			$praticiens[$i]["lieu"] = json_decode(getLieuById($data["id_lieu"]));
			
			$i++;
		}
		return json_encode($praticiens);
	}
	
	echo getPraticienByName($_POST["nom_praticien"]);
?>