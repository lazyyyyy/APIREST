<?php

	$loginRecupere = $_POST['login'];
	$mdpRecupere = $_POST['mdp'];
	
	function connexion($login, $mdp)
	{
		include("connexionBdd.php");
		$user = null;
		
		$req = $bdd->prepare("SELECT id_utilisateur id FROM connexion WHERE login = ? AND mdp = ?");
		$req->execute(array($login, $mdp));
		if($data = $req->fetch())
		{
			$user["id"] = $data["id"];
		}
		return json_encode($user);
	}
	
	echo connexion($loginRecupere,$mdpRecupere);
?>