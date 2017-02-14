<?php

	$loginRecupere = $_POST['login'];
	$mdpRecupere = $_POST['mdp'];
	
	function connexion($login, $mdp)
	{
		include("connexionBdd.php");
		$user_id = null;
		
		$req = $bdd->prepare("SELECT id_utilisateur id FROM connexion WHERE login = ? AND mdp = ?");
		$req->execute(array($login, $mdp));
		if($data = $req->fetch())
		{
			$user_id = $data["id"];
		}
		return json_encode($user_id);
	}
	
	echo connexion($loginRecupere,$mdpRecupere);
?>