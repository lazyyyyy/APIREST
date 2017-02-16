<?php

	$loginRecupere = $_POST['login'];
	$mdpRecupere = $_POST['mdp'];
	
	function connexion($login, $mdp)
	{
		include("connexionBdd.php");
		require_once("hashage.php");
		$user = null;
		
		$mdp = json_decode(hashage($mdp));
		
		$req = $bdd->prepare("SELECT id_utilisateur id FROM connexion WHERE login = ? AND mdp = ?");
		$req->execute(array($login, $mdp));
		if($data = $req->fetch())
		{
			$user["id"] = $data["id"];
			$user["message"] = "Bienvenue";
		}
		return json_encode($user);
	}
	
	echo connexion($loginRecupere, $mdpRecupere);
?>