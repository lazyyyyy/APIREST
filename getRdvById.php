<?php
	function getRdvById($id)
	{
		include("connexionBdd.php");
		
		$rdv = null;
		
		$req = $bdd->prepare("SELECT * FROM rdv WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$rdv["id"] = $data["id"];
			$rdv["date"] = $data["date"];
			$rdv["description"] = $data["description"];
			
			require_once("getPraticienById.php");
			$rdv["praticien"] = json_decode(getPraticienById($data["id_praticien"]));
			
			require_once("getUtilisateurById.php");
			$rdv["visiteur"] = json_decode(getUtilisateurById($data["id_visiteur"]));
			$rdv["utilisateur"] = json_decode(getUtilisateurById($data["id_utilisateur"]));
			
			require_once("getLieuById.php");
			$rdv["lieu"] = json_decode(getLieuById($data["id_lieu"]));
		}
		
		return json_encode($rdv);
	}
	
	echo getRdvById($_POST["id_rdv"]);
?>