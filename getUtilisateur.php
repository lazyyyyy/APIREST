<?php
	function getUtilisateurById($id)
	{
		require_once("connexionBdd.php");
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
			
			require_once("getLaboratoire.php");
			$user['laboratoire'] = json_decode(getLaboById($data['id_laboratoire']));
			
			require_once("getServiceComptable.php");
			$user['service_comptable'] = json_decode(getServiceComptableById($data['id_service_comptable']));
			
			require_once("getFonctionUtilisateur.php");
			$user['fonction_utilisateur'] = json_decode(getFonctionUtilisateurById($data['id_fonction_utilisateur']));
			
			require_once("getLieu.php");
			$user['lieu'] = json_decode(getLieuById($data['id_lieu']));
		}
		
		return json_encode($user);
	}
	
	$user = json_decode(getUtilisateurById(1));
	var_dump($user);
?>