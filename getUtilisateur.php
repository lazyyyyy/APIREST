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
			$user['date_embauche'] = $data['date_embauche'];
			$user['date_creation'] = $data['date_creation'];
			$user['date_modification'] = $data['date_modification'];
			$user['telephone_portable'] = $data['telephone_portable'];
			$user['telephone_fixe'] = $data['telephone_fixe'];
			$user['mail'] = $data['mail'];
			$user['id_laboratoire'] = $data['id_laboratoire'];
			$user['id_service_comptable'] = $data['id_service_comptable'];
			$user['id_fonction_utilisateur'] = $data['id_fonction_utilisateur'];
			$user['id_activite'] = $data['id_activite'];
			$user['id_lieu'] = $data['id_lieu'];
		}
		
		return json_encode($user);
	}
?>