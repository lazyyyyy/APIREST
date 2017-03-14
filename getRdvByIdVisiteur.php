<?php
	/*function getRdvByIdVisiteur($id_visiteur)
	{
		include("connexionBdd.php");
		
		$rdv = null;
		$i = 0;
		
		$req = $bdd->prepare("SELECT * FROM rdv WHERE id_visiteur = ?");
		$req->execute(array($id_visiteur));
		while($data = $req->fetch())
		{
			$rdv[$i]["id"] = $data["id"];
			$rdv[$i]["date"] = $data["date"];
			$rdv[$i]["description"] = $data["description"];
			
			require_once("fonctions.php");
			$rdv[$i]["praticien"] = json_decode(getPraticienById($data["id_praticien"]));
			
			$rdv[$i]["visiteur"] = json_decode(getUtilisateurById($data["id_visiteur"]));
			$rdv[$i]["utilisateur"] = json_decode(getUtilisateurById($data["id_utilisateur"]));
			
			$rdv[$i]["lieu"] = json_decode(getLieuById($data["id_lieu"]));
			
			$i++;
		}
		
		return json_encode($rdv);
	}*/
	require_once("fonctions.php");
	echo getRdvByIdVisiteur($_POST['id_visiteur']);
?>