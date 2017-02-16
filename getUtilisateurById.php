<?php
	function getUtilisateurById($id)
	{
		include("connexionBdd.php");
		$user = null;
		
		$req = $bdd->prepare('SELECT * FROM utilisateur WHERE id = ?');
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$user['id'] = $data['id'];
			$user['nom'] = $data['nom'];
			$user['prenom'] = $data['prenom'];
			$user["date_naissance"] = $data["date_naissance"];
			$user['date_embauche'] = $data['date_embauche'];
			$user['date_creation'] = $data['date_creation'];
			$user['date_modification'] = $data['date_modification'];
			$user['telephone_portable'] = $data['telephone_portable'];
			$user['telephone_fixe'] = $data['telephone_fixe'];
			$user['mail'] = $data['mail'];
			
			require_once("fonctions.php");
			$user['laboratoire'] = json_decode(getLaboById($data['id_laboratoire']));
			
			$user['service_comptable'] = json_decode(getServiceComptableById($data['id_service_comptable']));
			
			$user['fonction_utilisateur'] = json_decode(getFonctionUtilisateurById($data['id_fonction_utilisateur']));
			
			$user['lieu'] = json_decode(getLieuById($data['id_lieu']));
		}
		return json_encode($user);
	}
	
	echo getUtilisateurById($_POST["id_utilisateur"]);
?>