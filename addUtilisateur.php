<?php
	function addUtilisateur($nom, $prenom, $date_naissance, $date_embauche, $telephone_portable, $telephone_fixe, $mail, $id_laboratoire, $id_service_comptable, $id_fonction_utilisateur, $id_lieu)
	{
		include("connexionBdd.php");
		
		try
		{
			$req = $bdd->prepare("INSERT INTO utilisateur(nom, prenom, date_naissance, date_embauche, date_creation, date_modification, telephone_portable, telephone_fixe, mail, id_laboratoire, id_service_comptable, id_fonction_utilisateur, id_lieu) VALUES(?, ?, ?, ?, NOW(), NOW(), ?, ?, ?, ?, ?, ?, ?)");
			$data = $req->execute(array($nom, $prenom, $date_naissance, $date_embauche, $telephone_portable, $telephone_fixe, $mail, $id_laboratoire, $id_service_comptable, $id_fonction_utilisateur, $id_lieu));
			
			$req = $bdd->prepare("SELECT id FROM utilisateur WHERE mail = ?");
			$req->execute(array($mail));
			if($data = $req->fetch())
			{
				$id_utilisateur = $data["id"];
			}
			
			$login = strtolower($prenom).".".strtolower($nom);
			
			$mois = substr($date_naissance, 5, 2);
			switch($mois)
			{
				case "01" : $mois = "jan";
				break;
				case "02" : $mois = "feb";
				break;
				case "03" : $mois = "mar";
				break;
				case "04" : $mois = "apr";
				break;
				case "05" : $mois = "may";
				break;
				case "06" : $mois = "jun";
				break;
				case "07" : $mois = "jul";
				break;
				case "08" : $mois = "aug";
				break;
				case "09" : $mois = "sep";
				break;
				case "10" : $mois = "oct";
				break;
				case "11" : $mois = "nov";
				break;
				case "12" : $mois = "dec";
				break;
			}
		$mdp = substr($date_naissance, 8, 2)."-".$mois."-".substr($date_naissance, 0, 4);
		
		
		//HASHAGE MDP
		include("hashage.php");
		$mdp = json_decode(hashage($mdp));
		
		$req = $bdd->prepare("INSERT INTO connexion(login, mdp, id_utilisateur) VALUES(?, ?, ?)");
		$data = $req->execute(array($login, $mdp, $id_utilisateur));
			
		}catch(Exception $e)
		{
			echo $e->getMessage();
			$data = false;
		}
		
		return json_encode($data);
	}
	
	echo addUtilisateur($_POST["nom"], $_POST["prenom"], $_POST["date_naissance"], $_POST["date_embauche"], $_POST["telephone_portable"], $_POST["telephone_fixe"], $_POST["email"], $_POST["id_laboratoire"], $_POST["id_service_comptable"], $_POST["id_fonction_utilisateur"], $_POST["id_lieu"]);
?>