<?php
	function addUtilisateur($nom, $prenom, $date_embauche, $date_creation, $date_modification, $telephone_portable, $telephone_fixe, $mail, $id_laboratoire, $id_service_comptable, $id_fonction_utilisateur, $id_lieu)
	{
		include("connexionBdd.php");
		
		$req = $bdd->prepare("INSERT INTO utilisateur(nom, prenom, date_embauche, date_creation, date_modification, telephone_portable, telephone_fixe, mail, id_laboratoire, id_service_comptable, id_fonction_utilisateur, id_lieu) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$data = $req->execute(array($nom, $prenom, $date_embauche, $date_creation, $date_modification, $telephone_portable, $telephone_fixe, $mail, $id_laboratoire, $id_service_comptable, $id_fonction_utilisateur, $id_lieu));
		
		return json_encode($data);
	}
	
	var_dump(json_decode(addUtilisateur("Spadaro", "Florian", "2016-05-23", "2016-04-25", "2017-02-13", "0636625896", "0458963251", "spadaro.florian@outlook.fr", 1, 1, 1, 1)));
?>