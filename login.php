<?php
	function connexion($login, $mdp)
	{
		require_once("connexionBdd.php");
		$user_id = null;
		
		$req = $bdd->prepare("SELECT id_utilisateur id FROM connexion WHERE login = ? AND mdp = ?");
		$req->execute(array($login, $mdp));
		if($data = $req->fetch())
		{
			$user_id = $data["id"];
		}
		
		return json_encode($user_id);
	}
?>