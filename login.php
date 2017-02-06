<?php

    function Connexion($login, $mdp)
    {
		include("connexionBdd.php");
        $req = $bdd->prepare('SELECT utilisateur.id, connexion.login, connexion.mdp
							FROM connexion 
							JOIN utilisateur
							ON connexion.id_utilisateur = utilisateur.id
							WHERE connexion.login = ? AND connexion.mdp = ?');
        $req->execute(array($login, $mdp));
        
        if($data = $req->fetch())
        {
			$user['login'] = $data['login'];
			$user['mdp'] = $data['mdp'];
			
            $req2 = $bdd->prepare('SELECT * FROM utilisateur WHERE id = ?');
			$req2->execute(array($data['id']));
			if($data2 = $req2->fetch())
			{
				$user['id'] = $data2['id'];
				$user['nom'] = $data2['nom'];
				$user['prenom'] = $data2['prenom'];
				$user['date_embauche'] = $data2['date_embauche'];
				$user['date_creation'] = $data2['date_creation'];
				$user['date_modification'] = $data2['date_modification'];
				$user['telephone_portable'] = $data2['telephone_portable'];
				$user['telephone_fixe'] = $data2['telephone_fixe'];
				$user['mail'] = $data2['mail'];
				$user['id_laboratoire'] = $data2['id_laboratoire'];
				$user['id_service_comptable'] = $data2['id_service_comptable'];
				$user['id_fonction_utilisateur'] = $data2['id_fonction_utilisateur'];
				$user['id_activite'] = $data2['id_activite'];
				$user['id_lieu'] = $data2['id_lieu'];
				
			}
        }
		return json_encode($user);
    }
    
    /*$user = json_decode(Connexion("florian.spadaro", "florian"));
	
	foreach($user as $cle => $element)
	{
		echo $cle." => ".$element."</br>";
	}*/
?>