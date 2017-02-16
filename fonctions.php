<?php
	//J'ai copié toutes les fonctions pour ne pas appeler le "echo" des fonctions de l'API

	function getFamilleProduitById($id)
	{
		include("connexionBdd.php");
		$famille = null;
		
		$req = $bdd->prepare("SELECT * FROM famille_produit WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$famille["id"] = $data["id"];
			$famille["libelle"] = $data["libelle"];
			$famille["description"] = $data["description"];
		}
		
		return json_encode($famille);
	}
	
	function getFonctionPraticienById($id)
	{
		include("connexionBdd.php");
		$fonction = null;
		
		$req = $bdd->prepare("SELECT * FROM fonction_praticien WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$fonction["id"] = $data["id"];
			$fonction["libelle"] = $data["libelle"];
			$fonction["description"] = $data["description"];
		}
		
		return json_encode($fonction);
	}
	
	function getFonctionUtilisateurById($id)
	{
		include("connexionBdd.php");
		$fonction = null;
		
		$req = $bdd->prepare("SELECT * FROM fonction_utilisateur WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$fonction["id"] = $data["id"];
			$fonction["libelle"] = $data["libelle"];
			$fonction["description"] = $data["description"];
		}
		
		return json_encode($fonction);
	}
	
	function getLaboratoireById($id)
	{
		include("connexionBdd.php");
		$labo = null;
		
		$req = $bdd->prepare("SELECT * FROM laboratoire WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$labo["id"] = $data["id"];
			$labo["nom"] = $data["nom"];
			
			$labo["lieu"] = json_decode(getLieuById($data["id_lieu"]));
		}
		
		return json_encode($labo);
	}
	
	function getLieuById($id)
	{
		include("connexionBdd.php");
		$lieu = null;
		
		$req = $bdd->prepare("SELECT * FROM lieu WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$lieu["libelle"] = $data["libelle"];
			$lieu["adresse"] = $data["adresse"];
			$lieu["cp"] = $data["cp"];
			$lieu["ville"] = $data["ville"];
			$lieu["pays"] = $data["pays"];
			
			$lieu["region"] = json_decode(getRegionById($data["id"]));
		}
		
		return json_encode($lieu);
	}
	
	function getLieuByName($nom)
	{
		include("connexionBdd.php");
		$mot = "%".strtoupper($mot)."%";
		$i = 0;
		$lieu = null;
		
		$req = $bdd->prepare("SELECT * FROM lieu WHERE UCASE(libelle) LIKE ? OR UCASE(ville) LIKE ? OR UCASE(pays) LIKE ? OR UCASE(adresse) LIKE ? OR UCASE(cp) LIKE ?");
		$req->execute(array($mot, $mot, $mot, $mot, $mot));
		while($data = $req->fetch())
		{
			$lieu[$i]["libelle"] = $data["libelle"];
			$lieu[$i]["adresse"] = $data["adresse"];
			$lieu[$i]["cp"] = $data["cp"];
			$lieu[$i]["ville"] = $data["ville"];
			$lieu[$i]["pays"] = $data["pays"];
			
			$lieu[$i]["region"] = json_decode(getRegionById($data["id"]));
			
			$i++;
		}
		
		return json_encode($lieu);
	}
	
	function getMotifById($id)
	{
		include("connexionBdd.php");
		$motif = null;
		
		$req = $bdd->prepare("SELECT * FROM motif WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$motif["id"] = $data["id"];
			$motif["libelle"] = $data["libelle"];
			$motif["description"] = $data["description"];
		}
		
		return json_encode($motif);
	}
	
	function getParcAutoById($id)
	{
		include("connexionBdd.php");
		$parc_auto = null;
		
		$req = $bdd->prepare("SELECT * FROM parc_automobile WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$parc_auto["id"] = $data["id"];
			$parc_auto["libelle"] = $data["libelle"];
			
			$parc_auto["lieu"] = json_decode(getLieuById($data["id_lieu"]));
		}
		
		$req = $bdd->prepare("SELECT COUNT(*) nb_vehicule FROM vehicule WHERE id_parc_automobile = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$parc_auto["nb_total_vehicules"] = $data["nb_vehicule"];
		}
		
		$i = 0;
		$req = $bdd->prepare("SELECT immatricule, disponible FROM vehicule WHERE id_parc_automobile = ?");
		$req->execute(array($id));
		while($data = $req->fetch())
		{
			$parc_auto["liste_vehicules"][$i] = json_decode(getVehicule($data["immatricule"]));
			$i++;
		}
		
		$req = $bdd->prepare("SELECT COUNT(*) nb_vehicule FROM vehicule WHERE id_parc_automobile = ? AND disponible = 1");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$parc_auto["nb_vehicules_dispo"] = $data["nb_vehicule"];
		}
		
		$parc_auto["liste_vehicules_dispo"] = null;
		$j = 0;
		$req = $bdd->prepare("SELECT immatricule FROM vehicule WHERE id_parc_automobile = ? AND disponible = 1");
		$req->execute(array($id));
		while($data = $req->fetch())
		{
			$parc_auto["liste_vehicules_dispo"][$j] = json_decode(getVehicule($data["immatricule"]));
			$j++;
		}
		
		return json_encode($parc_auto);
	}
	
	function getPraticienById($id)
	{
		include("connexionBdd.php");
		
		$i = 0;
		$praticiens = null;
		
		$req = $bdd->prepare("SELECT * FROM praticien WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$praticiens["id"] = $data["id"];
			$praticiens["nom"] = $data["nom"];
			$praticiens["prenom"] = $data["prenom"];
			$praticiens["telephone_fixe"] = $data["telephone_fixe"];
			$praticiens["telephone_portable"] = $data["telephone_portable"];
			$praticiens["mail"] = $data["mail"];
			$praticiens["date_derniere_visite"] = $data["date_derniere_visite"];
			
			$praticiens["fonction"] = json_decode(getFonctionPraticienById($data["id_fonction_praticien"]));
			
			$praticiens["type_praticien"] = json_decode(getTypePraticienById($data["id_type_praticien"]));
			
			$praticiens["specialite"] = json_decode(getSpecialitePraticienById($data["id_specialite"]));
			
			$praticiens["lieu"] = json_decode(getLieuById($data["id_lieu"]));
			
			$i++;
		}
		return json_encode($praticiens);
	}
	
	function getPraticienByName($nom)
	{
		include("connexionBdd.php");
		
		$nom = "%".strtoupper($nom)."%";
		$i = 0;
		$praticiens = null;
		
		$req = $bdd->prepare("SELECT * FROM praticien WHERE UCASE(nom) LIKE ? ORDER BY nom");
		$req->execute(array($nom));
		while($data = $req->fetch())
		{
			$praticiens[$i]["id"] = $data["id"];
			$praticiens[$i]["nom"] = $data["nom"];
			$praticiens[$i]["prenom"] = $data["prenom"];
			$praticiens[$i]["telephone_fixe"] = $data["telephone_fixe"];
			$praticiens[$i]["telephone_portable"] = $data["telephone_portable"];
			$praticiens[$i]["mail"] = $data["mail"];
			$praticiens[$i]["date_derniere_visite"] = $data["date_derniere_visite"];
			
			$praticiens[$i]["fonction"] = json_decode(getFonctionPraticienById($data["id_fonction_praticien"]));
			
			$praticiens[$i]["type_praticien"] = json_decode(getTypePraticienById($data["id_type_praticien"]));
			
			$praticiens[$i]["specialite"] = json_decode(getSpecialitePraticienById($data["id_specialite"]));
			
			$praticiens[$i]["lieu"] = json_decode(getLieuById($data["id_lieu"]));
			
			$i++;
		}
		return json_encode($praticiens);
	}
	
	function getProduitByCompteRenduId($id) // id du compte rendu
	{
		include("connexionBdd.php");
		$produits = null;
		$i = 0;
		
		$req = $bdd->prepare("SELECT id_produit FROM compte_rendu_produit WHERE id_compte_rendu = ?");
		$req->execute(array($id));
		while($data = $req->fetch())
		{
			$produits[$i] = json_decode(getProduitById($data["id_produit"]));
			
			$i++;
		}
		
		return json_encode($produits);
	}
	
	function getProduitById($id)
	{
		include("connexionBdd.php");
		
		$produit = null;
		$i = 0;
		
		$req = $bdd->prepare("SELECT * FROM produit WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$produit["id"] = $data["id"];
			$produit["libelle"] = $data["libelle"];
			$produit["effets"] = $data["effets"];
			$produit["contre_indications"] = $data["contre_indications"];
			
			$produit["type_individu"] = json_decode(getTypeIndividuById($data["id_type_individu"]));
			
			$produit["composant"] = json_decode(getComposantById($data["id_composant"]));
			
			$produit["laboratoire"] = json_decode(getLaboratoireById($data["id_laboratoire"]));
			
			$produit["dosage"] = json_decode(getDosageById($data["id_dosage"]));
			
			$produit["famille"] = json_decode(getFamilleProduitById($data["id_famille_produit"]));
		}
		return json_encode($produit);
	}
	
	function getProduitByName($nom)
	{
		include("connexionBdd.php");
		
		$produit = null;
		$i = 0;
		$nom = "%".strtoupper($nom)."%";
		
		$req = $bdd->prepare("SELECT * FROM produit WHERE UCASE(libelle) LIKE ? ORDER BY libelle");
		$req->execute(array($nom));
		while($data = $req->fetch())
		{
			$produit[$i]["id"] = $data["id"];
			$produit[$i]["libelle"] = $data["libelle"];
			$produit[$i]["effets"] = $data["effets"];
			$produit[$i]["contre_indications"] = $data["contre_indications"];
			
			$produit[$i]["type_individu"] = json_decode(getTypeIndividuById($data["id_type_individu"]));
			
			$produit[$i]["composant"] = json_decode(getComposantById($data["id_composant"]));
			
			$produit[$i]["laboratoire"] = json_decode(getLaboById($data["id_laboratoire"]));
			
			$produit[$i]["dosage"] = json_decode(getDosageById($data["id_dosage"]));
			
			$produit[$i]["famille"] = json_decode(getFamilleProduitById($data["id_famille_produit"]));
			
			$i++;
		}
		return json_encode($produit);
	}
	
	function getRdvById($id)
	{
		include("connexionBdd.php");
		
		$rdv = null;
		
		$req = $bdd->prepare("SELECT * FROM rdv WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$rdv["id"] = $data["id"];
			$rdv["date"] = $data["date"];
			$rdv["description"] = $data["description"];
			
			$rdv["praticien"] = json_decode(getPraticienById($data["id_praticien"]));
			
			$rdv["visiteur"] = json_decode(getUtilisateurById($data["id_visiteur"]));
			$rdv["utilisateur"] = json_decode(getUtilisateurById($data["id_utilisateur"]));
			
			$rdv["lieu"] = json_decode(getLieuById($data["id_lieu"]));
		}
		
		return json_encode($rdv);
	}
	
	function getRegionById($id)
	{
		include("connexionBdd.php");
		$region = null;
		
		$req = $bdd->prepare("SELECT * FROM region WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$region["id"] = $data["id"];
			$region["libelle"] = $data["libelle"];
		}
		return json_encode($region);
	}
	
	function getRegionByLibelle($libelle)
	{
		include("connexionBdd.php");
		
		$libelle = "%".strtoupper($libelle)."%";
		$i = 0;
		$region = null;
		
		$req = $bdd->prepare("SELECT * FROM region WHERE libelle LIKE ?");
		$req->execute(array($libelle));
		while($data = $req->fetch())
		{
			$region[$i]["id"] = $data["id"];
			$region[$i]["libelle"] = $data["libelle"];
			
			$i++;
		}
		return json_encode($region);
	}
	
	function getServiceComptableById($id)
	{
		include("connexionBdd.php");
		$serviceComptable = null;
		
		$req = $bdd->prepare("SELECT * FROM service_comptable WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$serviceComptable["id"] = $data["id"];
			$serviceComptable["libelle"] = $data["libelle"];
			$serviceComptable["description"] = $data["description"];
			
			$serviceComptable["lieu"] = json_decode(getLieuById($data["id_lieu"]));
		}
		
		return json_encode($serviceComptable);
	}
	
	function getSpecialitePraticienById($id)
	{
		include("connexionBdd.php");
		$specialite = null;
		
		$req = $bdd->prepare("SELECT *FROM specialite WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$specialite["id"] = $data["id"];
			$specialite["libelle"] = $data["libelle"];
			$specialite["description"] = $data["description"];
		}
		
		return json_encode($specialite);
	}
	
	function getTypeIndividuById($id)
	{
		include("connexionBdd.php");
		$typeIndividu = null;
		
		$req = $bdd->prepare("SELECT * FROM type_individu WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$typeIndividu["id"] = $data["id"];
			$typeIndividu["libelle"] = $data["libelle"];
			$typeIndividu["description"] = $data["description"];
		}
		
		return json_encode($typeIndividu);
	}
	
	function getTypePraticienById($id)
	{
		include("connexionBdd.php");
		$type = null;
		
		$req = $bdd->prepare("SELECT * FROM type_praticien WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$type["id"] = $data["id"];
			$type["libelle"] = $data["libelle"];
			$type["description"] = $data["description"];
		}
		
		return json_encode($type);
	}
	
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
			
			$user['laboratoire'] = json_decode(getLaboById($data['id_laboratoire']));
			
			$user['service_comptable'] = json_decode(getServiceComptableById($data['id_service_comptable']));
			
			$user['fonction_utilisateur'] = json_decode(getFonctionUtilisateurById($data['id_fonction_utilisateur']));
			
			$user['lieu'] = json_decode(getLieuById($data['id_lieu']));
		}
		return json_encode($user);
	}
	
	function getVehicule($immatricule)
	{
		include("connexionBdd.php");
		$vehicule = null;
		
		$req = $bdd->prepare("SELECT * FROM vehicule WHERE immatricule = ?");
		$req->execute(array($immatricule));
		if($data = $req->fetch())
		{
			$vehicule["immatricule"] = $data["immatricule"];
			$vehicule["description"] = $data["description"];
			$vehicule["kilometrage"] = $data["kilometrage"];
			
			if($data["disponible"] == 1)
			{
				$vehicule["disponible"] = true;
			}
			else
			{
				$vehicule["disponible"] = false;
			}
			
			$vehicule["equipement"] = $data["equipement"];
			
			$req2 = $bdd->prepare("SELECT * FROM energie WHERE id = ?");
			$req2->execute(array($data["id_energie"]));
			if($data2 = $req2->fetch())
			{
				$vehicule["energie"]["id"] = $data2["id"];
				$vehicule["energie"]["libelle"] = $data2["libelle"];
				$vehicule["energie"]["description"] = $data2["description"];
			}
			
			$req2 = $bdd->prepare("SELECT * FROM type_vehicule WHERE id = ?");
			$req2->execute(array($data["id_type_vehicule"]));
			if($data2 = $req2->fetch())
			{
				$vehicule["type_vehicule"]["id"] = $data2["id"];
				$vehicule["type_vehicule"]["libelle"] = $data2["libelle"];
				$vehicule["type_vehicule"]["description"] = $data2["description"];
			}
			
			$req2 = $bdd->prepare("SELECT * FROM parc_automobile WHERE id = ?");
			$req2->execute(array($data["id_parc_automobile"]));
			if($data2 = $req2->fetch())
			{
				$vehicule["parc_automobile"]["id"] = $data2["id"];
				$vehicule["parc_automobile"]["libelle"] = $data2["libelle"];
				
				$vehicule["parc_automobile"]["lieu"] = json_decode(getLieuById($data2["id_lieu"]));
			}
		}
		
		return json_encode($vehicule);
	}
	
	function hashage($mot)
	{
		include("hashKey.php");
		$hash = crypt($mot, $key);
		
		return json_encode($hash);
	}
	
	function connexion($login, $mdp)
	{
		include("connexionBdd.php");
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
	
	function removeCompteRenduById($id)
	{
		include("connexionBdd.php");
		
		$req = $bdd->prepare("DELETE FROM compte_rendu_visite WHERE id = ?");
		$data = $req->execute(array($id));
		
		return json_encode($data); //retourne true ou false
	}
	
	function removeRdvById($id)
	{
		include ("connexionBdd.php");
		
		$req = $bdd->prepare("DELETE FROM rdv WHERE id = ?");
		$data = $req->execute(array($id));
		
		return json_encode($data); //retourne "true" ou "false"
	}
	
	function removeUtilisateur($id_utilisateur)
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
	}
	
	function removeVehiculeByImmatricule($immatricule)
	{
		include("connexionBdd.php");
		
		$req = $bdd->prepare("DELETE FROM vehicule WHERE immatricule = ?");
		$data = $req->execute(array($immatricule));
		
		return json_encode($data);
	}
	
	function rendreVehicule($id_utilisateur, $id_parc_automobile_arrivee, $distance_parcourue)
	{
		include("connexionBdd.php");
		
		$reponse = false;
		
		$req = $bdd->prepare("SELECT immatricule_vehicule  FROM vehicule_utilisateur WHERE id_utilisateur  = ? AND date_arrivee IS NULL");
		$req->execute(array($id_utilisateur));
		if($data = $req->fetch())
		{
			$req = $bdd->prepare("UPDATE vehicule SET disponible = 1 WHERE immatricule = ?");
			$reponse = $req->execute(array($data["immatricule"]));
			if($reponse)
			{
				$req = $bdd->prepare("UPDATE vehicule_utilisateur SET date_arrivee = NOW(), id_parc_automobile_arrivee = ?, distance_parcourue = ? WHERE id_utilisateur  = ? AND date_arrivee IS NULL");
				$reponse = $req->execute(array($id_parc_automobile_arrivee, $distance_parcourue, $id_utilisateur));
			}
		}
		
		return json_encode($reponse);
	}
	
	function reservationVehicule($immatricule, $id_utilisateur)
	{
		include("connexionBdd.php");
		
		$id_parc_auto = null;
		$reponse = false;
		
		$req = $bdd->prepare("SELECT COUNT(*) nb FROM vehicule_utilisateur WHERE id_utilisateur = ? AND date_arrivee IS NULL");
		$req->execute(array($id_utilisateur));
		if($data = $req->fetch())
		{
			if($data["nb"] == 0) // Verification si l'utilisateur possède déjà ou non un vehicule
			{
				$req = $bdd->prepare("SELECT id_parc_automobile, disponible FROM vehicule WHERE immatricule = ?");
				$req->execute(array($immatricule));
				if($data = $req->fetch())
				{
					if($data["disponible"] == 1) // Verification si le vehicule est disponible ou non
					{
						$id_parc_auto = $data["id_parc_automobile"];
						
						$req = $bdd->prepare("UPDATE vehicule SET disponible = 0 WHERE immatricule = ?");
						$reponse = $req->execute(array($immatricule));
						
						if($reponse)
						{
							$req = $bdd->prepare("INSERT INTO vehicule_utilisateur(id_utilisateur, immatricule_vehicule, date_depart, id_parc_automobile_depart) VALUES(?, ?, NOW(), ?)");
							$reponse = $req->execute(array($id_utilisateur, $immatricule, $id_parc_auto));
						}
					}
				}
			}
		}
		return json_encode($reponse);
	}
	
	function addCompteRendu($date, $bilan, $coef_confiance, $coef_notoriete, $coef_prescription, $id_motif, $id_praticien, $id_produit, $id_utilisateur)
	{
		include("connexionBdd.php");
		
		try
		{
			$req = $bdd->prepare("INSERT INTO compte_rendu_visite(date, bilan, coefficient_confiance, coefficient_notoriete, coefficient_prescription, id_motif, id_praticien, id_produit, id_utilisateur) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)");
			$data = $req->execute(array($date, $bilan, $coef_confiance, $coef_notoriete, $coef_prescription, $id_motif, $id_praticien, $id_produit, $id_utilisateur));
		}
		catch(Exception $e){
			$data = false;
		}
				
		return json_encode($data); //retourne true ou false
	}
	
	function addRdv($date, $commentaire, $id_praticien, $id_visiteur, $id_lieu, $id_utilisateur)
	{
		include("connexionBdd.php");
		
		try
		{
			$req = $bdd->prepare("INSERT INTO rdv(date, commentaire, id_praticien, id_visiteur, id_lieu, id_utilisateur) VALUES(?, ?, ?, ?, ?, ?)");
			$data = $req->execute(array($date, $commentaire, $id_praticien, $id_visiteur, $id_lieu, $id_utilisateur));
		}
		catch(Exception $e)
		{
			$data = false;
		}
		
		return json_encode($data); //retourne "true" ou "false"
	}
	
	function addUtilisateur($nom, $prenom, $date_naissance, $date_embauche, $telephone_portable, $telephone_fixe, $mail, $id_laboratoire, $id_service_comptable, $id_fonction_utilisateur, $id_lieu)
	{
		include("connexionBdd.php");
		
		try
		{
			$req = $bdd->prepare("INSERT INTO utilisateur(nom, prenom, date_naissance, date_embauche, date_creation, date_modification, telephone_portable, telephone_fixe, mail, id_laboratoire, id_service_comptable, id_fonction_utilisateur, id_lieu) VALUES(?, ?, ?, ?, NOW(), NOW(), ?, ?, ?, ?, ?, ?, ?)");
			$data = $req->execute(array($nom, $prenom, $date_naissance, $date_embauche, $telephone_portable, $telephone_fixe, $mail, $id_laboratoire, $id_service_comptable, $id_fonction_utilisateur, $id_lieu));
			
			$req = $bdd->prepare("SELECT id FROM utilisateur WHERE mail = ?");
			$req->execute(array($mail));
			if($data = $req->fetch())
			{
				$id_utilisateur = $data["id"];
			}
			
			$login = strtolower($prenom).".".strtolower($nom);
			
			$mois = substr($date_naissance, 5, 2);
			switch($mois)
			{
				case "01" : $mois = "jan";
				break;
				case "02" : $mois = "feb";
				break;
				case "03" : $mois = "mar";
				break;
				case "04" : $mois = "apr";
				break;
				case "05" : $mois = "may";
				break;
				case "06" : $mois = "jun";
				break;
				case "07" : $mois = "jul";
				break;
				case "08" : $mois = "aug";
				break;
				case "09" : $mois = "sep";
				break;
				case "10" : $mois = "oct";
				break;
				case "11" : $mois = "nov";
				break;
				case "12" : $mois = "dec";
				break;
			}
		$mdp = substr($date_naissance, 8, 2)."-".$mois."-".substr($date_naissance, 0, 4);
		
		
		//HASHAGE MDP
		include("hashage.php");
		$mdp = json_decode(hashage($mdp));
		
		$req = $bdd->prepare("INSERT INTO connexion(login, mdp, id_utilisateur) VALUES(?, ?, ?)");
		$data = $req->execute(array($login, $mdp, $id_utilisateur));
			
		}catch(Exception $e)
		{
			echo $e->getMessage();
			$data = false;
		}
		
		return json_encode($data);
	}
	
	function addVehicule($immatricule, $description, $kilometrage, $disponible, $equipement, $id_parc_automobile, $id_energie, $id_type_vehicule)
	{
		include("connexionBdd.php");
		
		try
		{
			$req = $bdd->prepare("INSERT INTO vehicule(immatricule, description, kilometrage, disponible, equipement, id_parc_automobile, id_energie, id_type_vehicule) VALUES(?, ?, ?, ?, ?, ?, ?, ?)");
			$data = $req->execute(array($immatricule, $description, $kilometrage, $disponible, $equipement, $id_parc_automobile, $id_energie, $id_type_vehicule));
		}
		catch(Excpetion $e)
		{
			$data = false;
		}
		
		return json_encode($data);
	}
	
	function getComposantById($id)
	{
		include("connexionBdd.php");
		$composant = null;
		
		$req = $bdd->prepare("SELECT * FROM composant WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$composant["id"] = $data["id"];
			$composant["libelle"] = $data["libelle"];
			$composant["description"] = $data["description"];
		}
		
		return json_encode($composant);
	}
	
	function getCompteRenduById($id)
	{
		include("connexionBdd.php");
		
		$compteRendu = null;
		
		$req = $bdd->prepare("SELECT * FROM compte_rendu_visite WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$compteRendu["id"] = $data["id"];
			$compteRendu["date"] = $data["date"];
			$compteRendu["bilan"] = $data["bilan"];
			$compteRendu["coef_confiance"] = $data["coefficient_confiance"];
			$compteRendu["coef_notoriete"] = $data["coefficient_notoriete"];
			$compteRendu["coef_prescription"] = $data["coefficient_prescription"];
			
			$compteRendu["motif"] = json_decode(getMotifById($data["id_motif"]));
			
			$compteRendu["echantillons"] = json_decode(getEchantillonByCompteRenduId($data["id"]));
			
			$compteRendu["praticien"] = json_decode(getPraticienById($data["id_praticien"]));
			
			$compteRendu["produits"] = json_decode(getProduitByCompteRenduId($data["id"]));
			
			$compteRendu["utilisateur"] = json_decode(getUtilisateurById($data["id_utilisateur"]));
		}
		
		return json_encode($compteRendu);
	}
	
	function getDosageById($id)
	{
		include("connexionBdd.php");
		$dosage = null;
		
		$req = $bdd->prepare("SELECT * FROM dosage WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$dosage["id"] = $data["id"];
			$dosage["quantite"] = $data["quantite"];
			$dosage["unite"] = $data["unite"];
			$dosage["description"] = $data["description"];
		}
		
		return json_encode($dosage);
	}
?>