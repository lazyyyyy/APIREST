<?php
	/*function removeUtilisateur($id_utilisateur)
	{
		include("connexionBdd.php");
		
		try{
			$req = $bdd->prepare("DELETE FROM connexion WHERE id_utilisateur = ?");
			$data = $req->execute(array($id_utilisateur));
			
			$req = $bdd->prepare("DELETE FROM utilisateur WHERE id = ?");
			$data = $req->execute(array($id_utilisateur));
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
			$data = false;
		}
		
		return json_encode($data);
	}*/
	require_once("fonctions.php");
	
	echo removeUtilisateur($_POST["id_utilisateur"]);
?>