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
			
			require_once("getFonctionPraticien.php");
			$praticiens[$i]["fonction"] = json_decode(getFonctionPraticienById($data["id_fonction_praticien"]));
			
			require_once("getTypePraticien.php");
			$praticiens[$i]["type_praticien"] = json_decode(getTypePraticienById($data["id_type_praticien"]));
			
			require_once("getSpecialitePraticien.php");
			$praticiens[$i]["specialite"] = json_decode(getSpecialitePraticienById($data["id_specialite"]));
			
			require_once("getLieu.php");
			$praticiens[$i]["lieu"] = json_decode(getLieuById($data["id_lieu"]));
			
			$i++;
		}
		return json_encode($praticiens);
	}
	
	function getPraticienById($id)
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
			
			require_once("getFonctionPraticien.php");
			$praticiens["fonction"] = json_decode(getFonctionPraticienById($data["id_fonction_praticien"]));
			
			require_once("getTypePraticien.php");
			$praticiens["type_praticien"] = json_decode(getTypePraticienById($data["id_type_praticien"]));
			
			require_once("getSpecialitePraticien.php");
			$praticiens["specialite"] = json_decode(getSpecialitePraticienById($data["id_specialite"]));
			
			require_once("getLieu.php");
			$praticiens["lieu"] = json_decode(getLieuById($data["id_lieu"]));
			
			$i++;
		}
		return json_encode($praticiens);
	}
?>