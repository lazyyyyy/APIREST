<?php
	//J'ai copié toutes les fonctions pour ne pas appeler le "echo" des fonctions de l'API
	
	function getRdvUtilisateurByDate($id_utilisateur, $date)
	{
		include("connexionBdd.php");
		$rdv = null;
		$i = 0;
		$req = $bdd->prepare("SELECT * FROM rdv WHERE DATE_FORMAT(date, '%Y-%m-%d') = ? AND id_utilisateur = ?");
		$req->execute(array($date, $id_utilisateur));
		while($data = $req->fetch())
		{
			$rdv[$i]["id"] = $data["id"];
			$rdv[$i]["date"] = $data["date"];
			$rdv[$i]["titre"] = $data["titre"];
			$rdv[$i]["description"] = $data["description"];
			$rdv[$i]["praticien"] = json_decode(getPraticienById($data["id_praticien"]));
			$rdv[$i]["lieu"] = json_decode(getLieuById($data["id_lieu"]));
			$rdv[$i]["utilisateur"] = json_decode(getUtilisateurById($data["id_utilisateur"]));
			
			$i++;
		}
		
		return json_encode($rdv);
	}
	
	function getRdvUtilisateurByAnnee($annee, $id_utilisateur)
	{
		include("connexionBdd.php");
		$rdv = null;
		$i = 0;
		$req = $bdd->prepare("SELECT id FROM rdv WHERE DATE_FORMAT(date, '%Y') = ? AND id_utilisateur = ?");
		$req->execute(array($annee, $id_utilisateur));
		while($data = $req->fetch())
		{
			$rdv[$i] = json_decode(getRdvById($data["id"]));
			$i++;
		}
		return json_encode($rdv);
	}
	
	function getIdentifiantByUtilisateurId($id)
	{
		include("connexionBdd.php");
		$identifiant = null;
		$req = $bdd->prepare("SELECT login FROM connexion WHERE id_utilisateur = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$identifiant = $data["login"];
		}
		return json_encode($identifiant);
	}
	
	function modifierLogin($login, $id_utilisateur)
	{
		include("connexionBdd.php");
		$data = false;
		try{
			$req = $bdd->prepare("UPDATE connexion SET login = ? WHERE id_utilisateur = ?");
			$data = $req->execute(array($login, $id_utilisateur));
		}catch(Exception $e){
			$data = false;
		}
		return json_encode($data);
	}
	
	function modifierMdp($mdp, $id_utilisateur)
	{
		include("connexionBdd.php");
		$data = false;
		try{
			$mdp = json_decode(hashage($mdp));
			$req = $bdd->prepare("UPDATE connexion SET mdp = ? WHERE id_utilisateur = ?");
			$data = $req->execute(array($mdp, $id_utilisateur));
		}catch(Exception $e)
		{
			$data = false;
		}
		
		return json_encode($data);
	}
	
	function addPj($url, $libelle, $id_frais)
	{
		include("connexionBdd.php");
		try{
			$req = $bdd->prepare("INSERT INTO justificatif(url, libelle, id_frais) VALUES(?, ?, ?)");
			$data = $req->execute(array($url, $libelle, $id_frais));
		}catch(Exception $e){
			$data = false;
		}
		
		return json_encode($data);
	}
	
	function getPjByIdFrais($id)
	{
		include("connexionBdd.php");
		$pj = null;
		$i = 0;
		
		$req = $bdd->prepare("SELECT * FROM justificatif WHERE id_frais = ?");
		$req->execute(array($id));
		while($data = $req->fetch())
		{
			$pj[$i]["id"] = $data["id"];
			$pj[$i]["url"] = $data["url"];
			$pj[$i]["libelle"] = $data["libelle"];
			
			$i++;
		}
		
		return json_encode($pj);
	}
	
	function addFrais($montant, $commentaire, $date, $id_utilisateur, $id_type_frais)
	{
		include("connexionBdd.php");
		try{
			$req = $bdd->prepare("INSERT INTO frais(montant, commentaire, date, id_utilisateur, id_type_frais, date_creation) VALUES(?, ?, ?, ?, ?, NOW())");
			$data = $req->execute(array($montant, $commentaire, $date, $id_utilisateur, $id_type_frais));
		}catch(Exception $e)
		{
			$data = false;
		}
		
		return json_encode($data);
	}
	
	function modifierFrais($montant, $commentaire, $date, $id_type_frais, $id_frais)
	{
		include("connexionBdd.php");
		try{
			$req = $bdd->prepare("UPDATE frais SET montant = ?, commentaire = ?, date = ?, id_type_frais = ?, date_modification = NOW() WHERE id = ?");
			$data = $req->execute(array($montant, $commentaire, $date, $id_type_frais, $id_frais));
		}catch(Exception $e)
		{
			$data = false;
		}
		
		return json_encode($data);
	}
	
	function removeFraisById($id)
	{
		include("connexionBdd.php");
		$data = false;
		try{
			$req = $bdd->prepare("SELECT * FROM justificatif WHERE id_frais = ?");
			$req->execute(array($id));
			while($rep = $req->fetch())
			{
				$url = "../html/".$rep["url"];
				unlink($url);
				$req2 = $bdd->prepare("DELETE FROM justificatif WHERE id = ?");
				$data2 = $req2->execute(array($rep["id"]));
			}
			$req = $bdd->prepare("DELETE FROM frais WHERE id = ?");
			$data = $req->execute(array($id));
		}catch(Exception $e){
			$data = false;
		}
		
		return json_encode($data);
	}
	
	function getIdFrais($montant, $commentaire, $date, $id_utilisateur, $id_type_frais)
	{
		include("connexionBdd.php");
		$idFrais = null;
		$req = $bdd->prepare("SELECT id FROM frais WHERE montant=? AND commentaire=? AND date=? AND id_utilisateur=? AND id_type_frais=?");
		$req->execute(array($montant, $commentaire, $date, $id_utilisateur, $id_type_frais));
		if($data = $req->fetch())
		{
			$idFrais = $data["id"];
		}
		
		return json_encode($idFrais);
	}
	
	function getFrais()
	{
		include("connexionBdd.php");
		$frais = null;
		$i = 0;
		$req = $bdd->query("SELECT * FROM frais ORDER BY date_creation DESC");
		while($data = $req->fetch())
		{
			$frais[$i]["id"] = $data["id"];
			$frais[$i]["montant"] = $data["montant"];
			$frais[$i]["commentaire"] = $data["commentaire"];
			$frais[$i]["date"] = $data["date"];
			$frais[$i]["utilisateur"] = json_decode(getUtilisateurById($data["id_utilisateur"]));
			$frais[$i]["type_frais"] = json_decode(getTypeDeFraisById($data["id_type_frais"]));
			$frais[$i]["date_creation"] = $data["date_creation"];
			$frais[$i]["date_modification"] = $data["date_modification"];
			
			$i++;
		}
		
		return json_encode($frais);
	}
	
	function getFraisById($id)
	{
		include("connexionBdd.php");
		$frais = null;
		$req = $bdd->prepare("SELECT * FROM frais WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$frais["id"] = $data["id"];
			$frais["montant"] = $data["montant"];
			$frais["commentaire"] = $data["commentaire"];
			$frais["date"] = $data["date"];
			$frais["utilisateur"] = json_decode(getUtilisateurById($data["id_utilisateur"]));
			$frais["type_frais"] = json_decode(getTypeDeFraisById($data["id_type_frais"]));
			$frais["date_creation"] = $data["date_creation"];
			$frais["date_modification"] = $data["date_modification"];
		}
		
		return json_encode($frais);
	}
	
	function getFraisByUtilisateurId($id)
	{
		include("connexionBdd.php");
		$frais = null;
		$i = 0;
		$req = $bdd->prepare("SELECT id FROM frais WHERE id_utilisateur = ? ORDER BY date_creation DESC");
		$req->execute(array($id));
		while($data = $req->fetch())
		{
			$frais[$i] = json_decode(getFraisById($data["id"]));
			$i++;
		}
		return json_encode($frais);
	}

	
	function getTypesDeFrais() //retourne la liste de tous les types de frais
	{
		include("connexionBdd.php");
		$typesDeFrais = null;
		$i = 0;
		$req = $bdd->query("SELECT * FROM type_frais");
		while($data = $req->fetch())
		{
			$typesDeFrais[$i]["id"] = $data["id"];
			$typesDeFrais[$i]["libelle"] = $data["libelle"];
			$typesDeFrais[$i]["description"] = $data["description"];
			
			$i++;
		}
		
		return json_encode($typesDeFrais);
	}
	
	function getTypeDeFraisById($id)
	{
		include("connexionBdd.php");
		$typeFrais = null;
		$req = $bdd->prepare("SELECT * FROM type_frais WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$typeFrais["id"] = $data["id"];
			$typeFrais["libelle"] = $data["libelle"];
			$typeFrais["description"] = $data["description"];
		}
		
		return json_encode($typeFrais);
	}

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
	
	function getFamilleProduitByName($nom)
	{
		include("connexionBdd.php");
		$familles = null;
		$i = 0;
		$nom = "%".$nom."%";
		$req = $bdd->prepare("SELECT id FROM famille_produit WHERE libelle LIKE ?");
		$req->execute(array($nom));
		while($data = $req->fetch())
		{
			$familles[$i] = json_decode(getFamilleProduitById($data["id"]));
			$i++;
		}
		return json_encode($familles);
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
	
	function getFonctionUtilisateurByName($nom)
	{
		include("connexionBdd.php");
		$fonctions = null;
		$i = 0;
		$nom = "%".$nom."%";
		$req = $bdd->prepare("SELECT * FROM fonction_utilisateur WHERE libelle LIKE ?");
		$req->execute(array($nom));
		while($data = $req->fetch())
		{
			$fonctions[$i]["id"] = $data["id"];
			$fonctions[$i]["libelle"] = $data["libelle"];
			$fonctions[$i]["description"] = $data["description"];
			$i++;
		}
		
		return json_encode($fonctions);
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
	
	function getLaboratoireByName($nom)
	{
		include("connexionBdd.php");
		$labos = null;
		$i = 0;
		$nom = "%".$nom."%";
		$req = $bdd->prepare("SELECT * FROM laboratoire WHERE nom LIKE ?");
		$req->execute(array($nom));
		while($data = $req->fetch())
		{
			$labos[$i] = json_decode(getLaboratoireById($data["id"]));
			$i++;
		}
		
		return json_encode($labos);
	}
	
	function addLieu($libelle, $adresse, $cp, $ville, $pays, $region_id)
	{
		include("connexionBdd.php");
		try{
			$req = $bdd->prepare("INSERT INTO lieu(libelle, adresse, cp, ville, pays, region_id) VALUES(?, ?, ?, ?, ?, ?)");
			$data = $req->execute(array($libelle, $adresse, $cp, $ville, $pays, $region_id));
		}catch(Exception $e)
		{
			$data = false;
		}
		
		return json_encode($data);
	}
	
	function getLieuById($id)
	{
		include("connexionBdd.php");
		$lieu = null;
		
		$req = $bdd->prepare("SELECT * FROM lieu WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$lieu["id"] = $data["id"];
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
		$mot = "%".strtoupper($nom)."%";
		$i = 0;
		$lieu = null;
		
		$req = $bdd->prepare("SELECT * FROM lieu WHERE UCASE(libelle) LIKE ?");
		$req->execute(array($mot));
		while($data = $req->fetch())
		{
			$lieu[$i]["id"] = $data["id"];
			$lieu[$i]["libelle"] = $data["libelle"];
			$lieu[$i]["adresse"] = $data["adresse"];
			$lieu[$i]["cp"] = $data["cp"];
			$lieu[$i]["ville"] = $data["ville"];
			$lieu[$i]["pays"] = $data["pays"];
			
			$lieu[$i]["region"] = json_decode(getRegionById($data["region_id"]));
			
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
	
	function getMotifByName($nom)
	{
		include("connexionBdd.php");
		$motifs = null;
		$i = 0;
		$libelle = "%".strtoupper($nom)."%";
		
		$req = $bdd->prepare("SELECT * FROM motif WHERE UCASE(libelle) LIKE ?");
		$req->execute(array($libelle));
		while($data = $req->fetch())
		{
			$motifs[$i]["id"] = $data["id"];
			$motifs[$i]["libelle"] = $data["libelle"];
			$motifs[$i]["description"] = $data["description"];
			$i++;
		}
		
		return json_encode($motifs);
	}
	
	function addParcAuto($libelle, $id_lieu, $adresseLieu, $cpLieu, $villeLieu, $paysLieu, $regionLieu)
	{
		include("connexionBdd.php");
		$data = false;
		try{
			$req = $bdd->prepare("UPDATE lieu SET adresse = ?, cp = ?, ville = ?, pays = ?, region_id = ? WHERE id = ?");
			$data = $req->execute(array($adresseLieu, $cpLieu, $villeLieu, $paysLieu, $regionLieu, $id_lieu));
			if($data)
			{
				$req = $bdd->prepare("INSERT INTO parc_automobile(libelle, id_lieu) VALUES(?, ?)");
				$data = $req->execute(array($libelle, $id_lieu));
			}
		}catch(Exception $e){
			$data = false;
		}
		return json_encode($data);
	}
	
	function modifierParcAuto($id, $libelle, $id_lieu, $adresseLieu, $cpLieu, $villeLieu, $paysLieu, $regionLieu)
	{
		include("connexionBdd.php");
		$data = false;
		try{
			$req = $bdd->prepare("UPDATE lieu SET adresse = ?, cp = ?, ville = ?, pays = ?, region_id = ? WHERE id = ?");
			$data = $req->execute(array($adresseLieu, $cpLieu, $villeLieu, $paysLieu, $regionLieu, $id_lieu));
			if($data)
			{
				$req = $bdd->prepare("UPDATE parc_automobile SET libelle = ?, id_lieu = ? WHERE id = ?");
				$data = $req->execute(array($libelle, $id_lieu, $id));
			}
		}catch(Exception $e){
			$data = false;
		}
		return json_encode($data);
	}
	
	function getParcAuto()
	{
		include("connexionBdd.php");
		$parcsAutos = null;
		$i = 0;
		$req = $bdd->query("SELECT id FROM parc_automobile");
		while($data = $req->fetch())
		{
			$parcsAutos[$i] = json_decode(getParcAutoById($data["id"]));
			$i++;
		}
		return json_encode($parcsAutos);
	}
	
	function removeParcAutoById($id)
	{
		include("connexionBdd.php");
		$data = false;
		try{
			$req = $bdd->prepare("DELETE FROM parc_automobile WHERE id = ?");
			$data = $req->execute(array($id));
		}catch(Exception $e){
			$data = false;
		}
		return json_encode($data);
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
	
	function getParcAutoByIdRegion($id_region)
	{
		include("connexionBdd.php");
		$parcs_autos = null;
		$i = 0;
		$req = $bdd->prepare("SELECT parc_automobile.id id FROM parc_automobile JOIN lieu ON lieu.id = parc_automobile.id_lieu WHERE lieu.region_id = ?");
		$req->execute(array($id_region));
		while($data = $req->fetch())
		{
			$parcs_autos[$i] = json_decode(getParcAutoById($data["id"]));
			$i++;
		}
		
		return json_encode($parcs_autos);
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
	
	function modifierPraticien($id, $nom, $prenom, $telFixe, $telPortable, $mail, $typePraticien, $specialite, $idLieu, $adresseLieu, $cpLieu, $villeLieu, $paysLieu, $regionLieu, $dateDerniereVisiste)
	{
		include("connexionBdd.php");
		$reponse = false;
		$req = $bdd->prepare("UPDATE lieu SET adresse = ?, cp = ?, ville = ?, pays = ?, region_id = ? WHERE id = ?");
		$data = $req->execute(array($adresseLieu, $cpLieu, $villeLieu, $paysLieu, $regionLieu, $idLieu));
		if($data)
		{
			$req = $bdd->prepare("UPDATE praticien SET nom = ?, prenom = ?, telephone_fixe = ?, telephone_portable = ?, mail = ?, id_type_praticien = ?, id_specialite = ?, id_lieu = ?, date_derniere_visite = ? WHERE id = ?");
			$data2 = $req->execute(array($nom, $prenom, $telFixe, $telPortable, $mail, $typePraticien, $specialite, $idLieu, $dateDerniereVisiste, $id));
			$reponse = $data2;
		}
		
		return json_encode($reponse);
	}
	
	function addPraticien($nom, $prenom, $telFixe, $telPortable, $mail, $dateDerniereVisiste, $typePraticien, $specialite, $idLieu, $adresseLieu, $cpLieu, $villeLieu, $paysLieu, $regionLieu)
	{
		include("connexionBdd.php");
		try{
			$req = $bdd->prepare("UPDATE lieu SET adresse = ?, cp = ?, ville = ?, pays = ?, region_id = ? WHERE id = ?");
			$data = $req->execute(array($adresseLieu, $cpLieu, $villeLieu, $paysLieu, $regionLieu, $idLieu));
			if($data)
			{
				$req = $bdd->prepare("INSERT INTO praticien(nom, prenom, telephone_fixe, telephone_portable, mail, date_derniere_visite, id_type_praticien, id_specialite, id_lieu) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)");
				$reponse = $req->execute(array($nom, $prenom, $telFixe, $telPortable, $mail, $dateDerniereVisiste, $typePraticien, $specialite, $idLieu));
			}
			
		}catch(Exception $e){
			$reponse = false;
		}
		
		return json_encode($reponse);
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
	
	function addProduit($libelle, $effets, $contre_indications, $dosage, $type_individu, $id_laboratoire, $id_famille, $composants)
	{
		include("connexionBdd.php");
		$composants = json_decode($composants);
		$data = false;
		try{
			$req = $bdd->prepare("INSERT INTO produit(libelle, effets, contre_indications, dosage, type_individu, id_laboratoire, id_famille_produit) VALUES(?, ?, ?, ?, ?, ?, ?)");
			$data = $req->execute(array($libelle, $effets, $contre_indications, $dosage, $type_individu, $id_laboratoire, $id_famille));
			if($data)
			{
				$req = $bdd->prepare("SELECT id FROM produit WHERE libelle = ? AND effets = ? AND contre_indications = ? AND dosage = ? AND type_individu = ? AND id_laboratoire = ? AND id_famille_produit = ?");
				$req->execute(array($libelle, $effets, $contre_indications, $dosage, $type_individu, $id_laboratoire, $id_famille));
				if($result = $req->fetch())
				{
					$idProduit = $result["id"];
					for($i = 0; $i < sizeof($composants); $i++)
					{
						$req = $bdd->prepare("INSERT INTO produit_composants(produit_id, composant_id) VALUES(?, ?)");
						$data = $req->execute(array($idProduit, $composants[$i]));
					}
				}
			}
		}catch(Exception $e){
			$data = false;
		}
		return json_encode($data);
	}
	
	function modiferProduit($id, $libelle, $effets, $contre_indications, $dosage, $type_individu, $id_laboratoire, $id_famille, $composants)
	{
		include("connexionBdd.php");
		$composants = json_decode($composants);
		$data = false;
		try{
			$req = $bdd->prepare("UPDATE produit SET libelle = ?, effets = ?, contre_indications = ?, dosage = ?, type_individu = ?, id_laboratoire = ?, id_famille_produit = ? WHERE id = ?");
			$data = $req->execute(array($libelle, $effets, $contre_indications, $dosage, $type_individu, $id_laboratoire, $id_famille, $id));
			if($data){
				$req = $bdd->prepare("DELETE FROM produit_composants WHERE produit_id = ?");
				$data = $req->execute(array($id));
				if($data)
				{
					for($i = 0; $i < sizeof($composants); $i++)
					{
						$req = $bdd->prepare("INSERT INTO produit_composants(produit_id, composant_id) VALUES(?, ?)");
						$data = $req->execute(array($id, $composants[$i]));
					}
				}
			}
		}catch(Exception $e){
			$data = false;
		}
		return json_encode($data);
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
			
			$produit["type_individu"] = $data["type_individu"];
			
			$produit["composant"] = null;
			$i = 0;
			$req2 = $bdd->prepare("SELECT * FROM produit_composants WHERE produit_id = ?");
			$req2->execute(array($data["id"]));
			while($data2 = $req2->fetch())
			{
				$produit["composant"][$i] = json_decode(getComposantById($data2["composant_id"]));
				$i++;
			}
			//$produit["composant"] = json_decode(getComposantById($data["id_composant"]));
			
			$produit["laboratoire"] = json_decode(getLaboratoireById($data["id_laboratoire"]));
			
			$produit["dosage"] = $data["dosage"];
			
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
			
			$produit[$i]["type_individu"] = $data["type_individu"];
			
			$j = 0;
			$req2 = $bdd->prepare("SELECT * FROM produit_composants WHERE produit_id = ?");
			$req2->execute(array($data["id"]));
			while($data2 = $req2->fetch())
			{
				$produit[$i]["composant"][$j] = json_decode(getComposantById($data2["composant_id"]));
				$j++;
			}
			//$produit[$i]["composant"] = json_decode(getComposantById($data["id_composant"]));
			
			$produit[$i]["laboratoire"] = json_decode(getLaboratoireById($data["id_laboratoire"]));
			
			$produit[$i]["dosage"] = $data["dosage"];
			
			$produit[$i]["famille"] = json_decode(getFamilleProduitById($data["id_famille_produit"]));
			
			$i++;
		}
		return json_encode($produit);
	}
	
	function removeProduitById($id)
	{
		include("connexionBdd.php");
		$data = false;
		try{
			$req = $bdd->prepare("DELETE FROM produit_composants WHERE produit_id = ?");
			$data = $req->execute(array($id));
			$req = $bdd->prepare("DELETE FROM produit WHERE id = ?");
			$data = $req->execute(array($id));
		}catch(Exception $e){
			$data = false;
		}
		
		return json_encode($data);
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
			$rdv["titre"] = $data["titre"];
			$rdv["date"] = $data["date"];
			$rdv["description"] = $data["description"];
			
			$rdv["praticien"] = json_decode(getPraticienById($data["id_praticien"]));
			
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
	
	function getRegionByName($nom)
	{
		include("connexionBdd.php");
		$regions = null;
		$i = 0;
		$nom = "%".$nom."%";
		
		$req = $bdd->prepare("SELECT * FROM region WHERE libelle LIKE ?");
		$req->execute(array($nom));
		while($data = $req->fetch())
		{
			$regions[$i]["id"] = $data["id"];
			$regions[$i]["libelle"] = $data["libelle"];
			$i++;
		}
		
		return json_encode($regions);
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
	
	function getSpecialitePraticienByName($nom)
	{
		include("connexionBdd.php");
		$specialites = null;
		$i = 0;
		$nom = "%".$nom."%";
		
		$req = $bdd->prepare("SELECT * FROM specialite WHERE libelle LIKE ?");
		$req->execute(array($nom));
		while($data = $req->fetch())
		{
			$specialites[$i]["id"] = $data["id"];
			$specialites[$i]["libelle"] = $data["libelle"];
			$specialites[$i]["description"] = $data["description"];
			
			$i++;
		}
		
		return json_encode($specialites);
	}
	
	function getEnergieVehiculeByName($nom)
	{
		include("connexionBdd.php");
		$energies = null;
		$i = 0;
		$nom = "%".$nom."%";
		$req = $bdd->prepare("SELECT id FROM energie WHERE libelle LIKE ? ");
		$req->execute(array($nom));
		while($data = $req->fetch())
		{
			$energies[$i] = json_decode(getEnergieVehiculeById($data["id"]));
			$i++;
		}
		return json_encode($energies);
	}
	
	function getEnergieVehiculeById($id)
	{
		include("connexionBdd.php");
		$energie = null;
		$req = $bdd->prepare("SELECT * FROM energie WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$energie["id"] = $data["id"];
			$energie["libelle"] = $data["libelle"];
			$energie["description"] = $data["description"];
		}
		return json_encode($energie);
	}
	
	function getTypeVehiculeByName($nom)
	{
		include("connexionBdd.php");
		$typesVehicules = null;
		$i = 0;
		$nom = "%".$nom."%";
		$req = $bdd->prepare("SELECT id FROM type_vehicule WHERE libelle LIKE ? ");
		$req->execute(array($nom));
		while($data = $req->fetch())
		{
			$typesVehicules[$i] = json_decode(getTypeVehiculeById($data["id"]));
			$i++;
		}
		return json_encode($typesVehicules);
	}
	
	function getTypeVehiculeById($id)
	{
		include("connexionBdd.php");
		$typeVehicule = null;
		$req = $bdd->prepare("SELECT * FROM type_vehicule WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$typeVehicule["id"] = $data["id"];
			$typeVehicule["libelle"] = $data["libelle"];
			$typeVehicule["description"] = $data["description"];
		}
		return json_encode($typeVehicule);
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
	
	function getTypePraticienByName($nom)
	{
		include("connexionBdd.php");
		$types = null;
		$i = 0;
		$nom = "%".$nom."%";
		
		$req = $bdd->prepare("SELECT * FROM type_praticien WHERE libelle LIKE ?");
		$req->execute(array($nom));
		while($data = $req->fetch())
		{
			$types[$i]["id"] = $data["id"];
			$types[$i]["libelle"] = $data["libelle"];
			$types[$i]["description"] = $data["description"];
			
			$i++;
		}
		
		return json_encode($types);
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
			
			$user['laboratoire'] = json_decode(getLaboratoireById($data['id_laboratoire']));
			
			$user['service_comptable'] = json_decode(getServiceComptableById($data['id_service_comptable']));
			
			$user['fonction_utilisateur'] = json_decode(getFonctionUtilisateurById($data['id_fonction_utilisateur']));
			
			$user['lieu'] = json_decode(getLieuById($data['id_lieu']));
		}
		return json_encode($user);
	}
	
	function getUtilisateurByName($nom)
	{
		include("connexionBdd.php");
		$users = null;
		$i = 0;
		$nom = "%".strtoupper($nom)."%";
		$req = $bdd->prepare("SELECT id FROM utilisateur WHERE UCASE(nom) LIKE ?");
		$req->execute(array($nom));
		while($data = $req->fetch())
		{
			$users[$i] = json_decode(getUtilisateurById($data["id"]));
			$i++;
		}
		return json_encode($users);
	}
	
	function modifierUtilisateur($id, $nom, $prenom, $dateNaissance, $dateEmbauche, $telFixe, $telPortable, $mail, $idFonction, $idLabo)
	{
		include("connexionBdd.php");
		$data = false;
		try{
			$req = $bdd->prepare("UPDATE utilisateur SET nom = ?, prenom = ?, date_naissance = ?, date_embauche = ?, date_modification = NOW(), telephone_fixe = ?, telephone_portable = ?, mail = ?, id_fonction_utilisateur = ?, id_laboratoire = ? WHERE id = ?");
			$data = $req->execute(array($nom, $prenom, $dateNaissance, $dateEmbauche, $telFixe, $telPortable, $mail, $idFonction, $idLabo, $id));
		}catch(Exception $e){
			$data = false;
		}
		return json_encode($data);
	}
	
	function getMarqueVehiculeById($id)
	{
		include("connexionBdd.php");
		$marque = null;
		$req = $bdd->prepare("SELECT * FROM vehicule_marque WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$marque["id"] = $data["id"];
			$marque["libelle"] = $data["libelle"];
		}
		return json_encode($marque);
	}
	
	function getMarquesVehiculesByName($nom)
	{
		include("connexionBdd.php");
		$marques = null;
		$i = 0;
		$nom = "%".$nom."%";
		$req = $bdd->prepare("SELECT id FROM vehicule_marque WHERE libelle LIKE ?");
		$req->execute(array($nom));
		while($data = $req->fetch())
		{
			$marques[$i] = json_decode(getMarqueVehiculeById($data["id"]));
			$i++;
		}
		return json_encode($marques);
	}
	
	function getModelVehiculeById($id)
	{
		include("connexionBdd.php");
		$model = null;
		$req = $bdd->prepare("SELECT * FROM vehicule_model WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$model["id"] = $data["id"];
			$model["libelle"] = $data["libelle"];
			$model["marque"] = json_decode(getMarqueVehiculeById($data["id_marque"]));
		}
		return json_encode($model);
	}
	
	function getModelsVehiculesByMarqueId($id)
	{
		include("connexionBdd.php");
		$models = null;
		$i = 0;
		$req = $bdd->prepare("SELECT id FROM vehicule_model WHERE id_marque = ?");
		$req->execute(array($id));
		while($data = $req->fetch())
		{
			$models[$i] = json_decode(getModelVehiculeById($data["id"]));
			$i++;
		}
		return json_encode($models);
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
			$vehicule["modele"] = json_decode(getModelVehiculeById($data["id_model"]));
			$vehicule["marque"] = json_decode(getMarqueVehiculeById($data["id_marque"]));
			$vehicule["image"] = json_decode(getImagesVehicule($immatricule));
			
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
	
	function getImagesVehicule($immatricule)
	{
		include("connexionBdd.php");
		$images = null;
		$i = 0;
		$req = $bdd->prepare("SELECT * FROM vehicule_images WHERE immatricule_vehicule = ?");
		$req->execute(array($immatricule));
		while($data = $req->fetch())
		{
			$images[$i]["id"] = $data["id"];
			$images[$i]["immatricule_vehicule"] = $data["immatricule_vehicule"];
			$images[$i]["image"] = $data["image"];
			$i++;
		}
		return json_encode($images);
	}
	
	function removeImageVehicule($id)
	{
		include("connexionBdd.php");
		$data = false;
		try{
			$req = $bdd->prepare("SELECT image FROM vehicule_images WHERE id = ?");
			$req->execute(array($id));
			if($data = $req->fetch())
			{
				$image = $data["image"];
				unlink("../".$image);
			}
			$req = $bdd->prepare("DELETE FROM vehicule_images WHERE id = ?");
			$req->execute(array($id));
		}catch(Exception $e){
			$data = false;
		}
		return json_encode($data);
	}
	
	function addImageVehicule($destination, $immatricule)
	{
		include("connexionBdd.php");
		$data = false;
		try{
			$req = $bdd->prepare("INSERT INTO vehicule_images(immatricule_vehicule, image) VALUES(?, ?)");
			$data = $req->execute(array($immatricule, $destination));
		}catch(Exception $e){
			$data = false;
		}
		
		return json_encode($data);
	}
	
	function getVehiculesListe()
	{
		include("connexionBdd.php");
		$vehicules = null;
		$i = 0;
		$req = $bdd->query("SELECT immatricule FROM vehicule");
		while($data = $req->fetch())
		{
			$vehicules[$i] = json_decode(getVehicule($data["immatricule"]));
			$i++;
		}
		return json_encode($vehicules);
	}
	
	function getVehiculeByParcAutoId($id_parc_auto)
	{
		include("connexionBdd.php");
		$vehicules = null;
		$i = 0;
		$req = $bdd->prepare("SELECT immatricule FROM vehicule WHERE id_parc_automobile = ? AND disponible = 1");
		$req->execute(array($id_parc_auto));
		while($data = $req->fetch())
		{
			$vehicules[$i] = json_decode(getVehicule($data["immatricule"]));
			$i++;
		}
		
		return json_encode($vehicules);
	}
	
	function hashage($mot)
	{
		$hash = md5($mot);
		
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
		}else{
			$user["id"] = null;
			$user["message"] = "N'arrive pas a se connecter";
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
	
	function removePraticien($id_praticien)
	{
		include("connexionBdd.php");
		
		try{
			$req = $bdd->prepare("DELETE FROM praticien WHERE id = ?");
			$req->execute(array($id_praticien));
			$data = true;
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
		$data = false;
		try{
			$req = $bdd->prepare("SELECT * FROM vehicule_images WHERE immatricule_vehicule = ?");
			$req->execute(array($immatricule));
			while($data = $req->fetch())
			{
				$image = $data["image"];
				unlink("../html/".$image);
			}
			$req = $bdd->prepare("DELETE FROM vehicule_images WHERE immatricule_vehicule = ?");
			$req->execute(array($immatricule));
			$req = $bdd->prepare("DELETE FROM vehicule WHERE immatricule = ?");
			$data = $req->execute(array($immatricule));
		}catch(Exception $e)
		{
			$data = false;
		}
	
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
			$req = $bdd->prepare("UPDATE vehicule SET disponible = 1, id_parc_automobile = ? WHERE immatricule = ?");
			$reponse = $req->execute(array($id_parc_automobile_arrivee, $data["immatricule_vehicule"]));
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
					else{
						return json_encode(0); //retourne 0 si le vehicule n'est pas disponible
					}
				}
			}
			else{
				return json_encode(null); // retourne null si l'utilisateur possède déjà un véhicule
			}
		}
		return json_encode($reponse);
	}
	
	function getVehiculesReservesByUtilisateurId($id_utilisateur)
	{
		include("connexionBdd.php");
		$reservations = null;
		$i = 0;
		$req = $bdd->prepare("SELECT * FROM vehicule_utilisateur WHERE id_utilisateur = ? ORDER BY date_depart");
		$req->execute(array($id_utilisateur));
		while($data = $req->fetch())
		{
			$reservations[$i]["id"] = $data["id"];
			$reservations[$i]["utilisateur"] = json_decode(getUtilisateurById($data["id_utilisateur"]));
			$reservations[$i]["vehicule"] = json_decode(getVehicule($data["immatricule_vehicule"]));
			$reservations[$i]["date_depart"] = $data["date_depart"];
			$reservations[$i]["date_arrivee"] = $data["date_arrivee"];
			$reservations[$i]["parc_auto_depart"] = json_decode(getParcAutoById($data["id_parc_automobile_depart"]));
			$reservations[$i]["parc_auto_arrivee"] = json_decode(getParcAutoById($data["id_parc_automobile_arrivee"]));
			$reservations[$i]["distance_parcourue"] = $data["distance_parcourue"];
			$i++;
		}
		return json_encode($reservations);
	}
	
	function getReservationById($id)
	{
		include("connexionBdd.php");
		$reservation = null;
		$req = $bdd->prepare("SELECT * FROM vehicule_utilisateur WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$reservation["id"] = $data["id"];
			$reservation["utilisateur"] = json_decode(getUtilisateurById($data["id_utilisateur"]));
			$reservation["vehicule"] = json_decode(getVehicule($data["immatricule_vehicule"]));
			$reservation["date_depart"] = $data["date_depart"];
			$reservation["date_arrivee"] = $data["date_arrivee"];
			$reservation["parc_auto_depart"] = json_decode(getParcAutoById($data["id_parc_automobile_depart"]));
			$reservation["parc_auto_arrivee"] = json_decode(getParcAutoById($data["id_parc_automobile_arrivee"]));
			$reservation["distance_parcourue"] = $data["distance_parcourue"];
		}
		return json_encode($reservation);
	}
	
	function getReservationByParcAutoId($id)
	{
		include("connexionBdd.php");
		$reservations = null;
		$i = 0;
		$req = $bdd->prepare("SELECT id FROM vehicule_utilisateur WHERE id_parc_automobile_depart = ? OR id_parc_automobile_arrivee = ?");
		$req->execute(array($id, $id));
		while($data = $req->fetch())
		{
			$reservations[$i] = json_decode(getReservationById($data["id"]));
			$i++;
		}
		return json_encode($reservations);
	}
	
	function addCompteRendu($date, $bilan, $coef_confiance, $coef_notoriete, $coef_prescription, $id_motif, $autre_motif, $id_praticien, $id_produit, $id_utilisateur, $nb_echantillons)
	{
		include("connexionBdd.php");
		
		try
		{
			$req = $bdd->prepare("INSERT INTO compte_rendu_visite(date, bilan, coefficient_confiance, coefficient_notoriete, coefficient_prescription, id_motif, id_praticien, id_produit, id_utilisateur, nb_echantillons, date_creation) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
			$data = $req->execute(array($date, $bilan, $coef_confiance, $coef_notoriete, $coef_prescription, $id_motif, $id_praticien, $id_produit, $id_utilisateur, $nb_echantillons));
		}
		catch(Exception $e){
			$data = false;
		}
		
		return json_encode($data); //retourne true ou false
	}
	
	function modifierCompteRendu($id, $date, $bilan, $coef_confiance, $coef_notoriete, $coef_prescription, $id_motif, $id_praticien, $id_produit, $nb_echantillons)
	{
		include("connexionBdd.php");
		
		try
		{
			$req = $bdd->prepare("UPDATE compte_rendu_visite SET date = ?, bilan = ?, coefficient_confiance = ?, coefficient_notoriete = ?, coefficient_prescription = ?, id_motif = ?, id_praticien = ?, id_produit = ?, nb_echantillons = ?, date_modification = NOW() WHERE id = ?");
			$data = $req->execute(array($date, $bilan, $coef_confiance, $coef_notoriete, $coef_prescription, $id_motif, $id_praticien, $id_produit, $nb_echantillons, $id));
		}
		catch(Exception $e){
			$data = false;
		}
		
		return json_encode($data); //retourne true ou false
	}
	
	function addRdv($date, $description, $id_praticien, $id_visiteur, $id_lieu, $id_utilisateur)
	{
		include("connexionBdd.php");
		
		try
		{
			$req = $bdd->prepare("INSERT INTO rdv(date, description, id_praticien, id_visiteur, id_lieu, id_utilisateur) VALUES(?, ?, ?, ?, ?, ?)");
			$data = $req->execute(array($date, $description, $id_praticien, $id_visiteur, $id_lieu, $id_utilisateur));
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
			$utilisateur = null;
			$req = $bdd->prepare("INSERT INTO utilisateur(nom, prenom, date_naissance, date_embauche, date_creation, date_modification, telephone_portable, telephone_fixe, mail, id_laboratoire, id_service_comptable, id_fonction_utilisateur, id_lieu) VALUES(?, ?, ?, ?, NOW(), NOW(), ?, ?, ?, ?, ?, ?, ?)");
			$data = $req->execute(array($nom, $prenom, $date_naissance, $date_embauche, $telephone_portable, $telephone_fixe, $mail, $id_laboratoire, $id_service_comptable, $id_fonction_utilisateur, $id_lieu));
			
			$req = $bdd->prepare("SELECT id FROM utilisateur WHERE mail = ?");
			$req->execute(array($mail));
			if($data = $req->fetch())
			{
				$id_utilisateur = $data["id"];
			}
			
			$login = strtolower($prenom).".".strtolower($nom);
			$utilisateur["login"] = $login;
			
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
		$utilisateur["mdp"] = $mdp;
		//mot de passe créé automatiquement = "06-may-2005" par exemple, soit le la date d'anniversaire avec: jour-mois(3 premières lettres en anglais)-année
		
		
		//HASHAGE MDP
		//include("hashage.php");
		$mdp = json_decode(hashage($mdp));
		
		$req = $bdd->prepare("INSERT INTO connexion(login, mdp, id_utilisateur) VALUES(?, ?, ?)");
		$data = $req->execute(array($login, $mdp, $id_utilisateur));
		$utilisateur["creation"] = true;
			
		}catch(Exception $e)
		{
			echo $e->getMessage();
			$utilisateur["creation"] = false;
			$utilisateur["login"] = null;
			$utilisateur["mdp"] = null;
		}
		
		return json_encode($utilisateur);
	}
	
	function addVehicule($immatricule, $marque, $model, $description, $kilometrage, $equipement, $id_parc_automobile, $id_energie, $id_type_vehicule, $image)
	{
		include("connexionBdd.php");
		
		$data = false;
		try
		{
			$req = $bdd->prepare("INSERT INTO vehicule(immatricule, id_marque, id_model, description, kilometrage, disponible, equipement, id_parc_automobile, id_energie, id_type_vehicule) VALUES(?, ?, ?, ?, ?, 1, ?, ?, ?, ?)");
			$data = $req->execute(array($immatricule, $marque, $model, $description, $kilometrage, $equipement, $id_parc_automobile, $id_energie, $id_type_vehicule));
		}
		catch(Excpetion $e)
		{
			$data = false;
		}
		
		return json_encode($data);
	}
	
	function modifierVehicule($ancien_immatricule, $nouveau_immatricule, $marque, $model, $description, $kilometrage, $equipement, $id_parc_automobile, $id_energie, $id_type_vehicule)
	{
		include("connexionBdd.php");
		
		$data = false;
		try
		{
			$req = $bdd->prepare("UPDATE vehicule SET immatricule = ?, id_marque = ?, id_model = ?, description = ?, kilometrage = ?, equipement = ?, id_parc_automobile = ?, id_energie = ?, id_type_vehicule = ? WHERE immatricule = ?");
			$data = $req->execute(array($nouveau_immatricule, $marque, $model, $description, $kilometrage, $equipement, $id_parc_automobile, $id_energie, $id_type_vehicule, $ancien_immatricule));
		}
		catch(Excpetion $e)
		{
			$data = false;
		}
		
		return json_encode($data);
	}
	
	function modifierImageVehicule($url, $immatricule)
	{
		include("connexionBdd.php");
		$data = false;
		try{
			$req = $bdd->prepare("SELECT image FROM vehicule WHERE immatricule = ?");
			$req->execute(array($immatricule));
			if($reponse = $req->fetch())
			{
				$image = $reponse["image"];
				$suppr = unlink("../".$image);
				if($suppr)
				{
					$req = $bdd->prepare("UPDATE vehicule SET image = ? WHERE immatricule = ?");
					$data = $req->execute(array($url, $immatricule));
				}
				else{
					$data = false;
				}
			}
			else{
				$data = false;
			}
		}catch(Exception $e){
			$data = false;
		}
		return json_encode($data);
	}
	
	function modifierPjFrais($destination, $nom_fichier, $idFrais)
	{
		include("connexionBdd.php");
		$data = false;
		try{
			$req = $bdd->prepare("SELECT url FROM justificatif WHERE id_frais = ?");
			$req->execute(array($idFrais));
			while($reponse = $req->fetch())
			{
				$url = $reponse["url"];
				$suppr = unlink("../".$url);
				if($suppr)
				{
					$req = $bdd->prepare("DELETE FROM justificatif WHERE id_frais = ?");
					$data = $req->execute(array($idFrais));
					if($data)
					{
						$data = json_decode(addPj($destination, $nom_fichier, $idFrais));
					}
				}
				else{
					$data = false;
				}
			}
		}catch(Exception $e){
			$data = false;
		}
		return json_encode($data);
	}
	
	function getReservationsByImmatriculeVehicule($immatricule)
	{
		include("connexionBdd.php");
		$reservations = null;
		$i = 0;
		$req = $bdd->prepare("SELECT id FROM vehicule_utilisateur WHERE immatricule_vehicule = ?");
		$req->execute(array($immatricule));
		while($data = $req->fetch())
		{
			$reservations[$i] = json_decode(getReservationById($data["id"]));
			$i++;
		}
		return json_encode($reservations);
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
	
	function getComposantByName($nom)
	{
		include("connexionBdd.php");
		$composants = null;
		$i = 0;
		$nom = "%".$nom."%";
		$req = $bdd->prepare("SELECT id FROM composant WHERE libelle LIKE ?");
		$req->execute(array($nom));
		while($data = $req->fetch())
		{
			$composants[$i] = json_decode(getComposantById($data["id"]));
			$i++;
		}
		return json_encode($composants);
	}
	
	function getComptesRendus()
	{
		include("connexionBdd.php");
		$comptesRendus = null;
		$i = 0;
		$req = $bdd->query("SELECT id FROM compte_rendu_visite ORDER BY date_creation DESC");
		while($data = $req->fetch())
		{
			$comptesRendus[$i] = json_decode(getCompteRenduById($data["id"]));
			$i++;
		}
		return json_encode($comptesRendus);
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
			$compteRendu["date_creation"] = $data["date_creation"];
			$compteRendu["date_modification"] = $data["date_modification"];
			$compteRendu["bilan"] = $data["bilan"];
			$compteRendu["coef_confiance"] = $data["coefficient_confiance"];
			$compteRendu["coef_notoriete"] = $data["coefficient_notoriete"];
			$compteRendu["coef_prescription"] = $data["coefficient_prescription"];
			$compteRendu["nb_echantillons"] = $data["nb_echantillons"];
			
			
			$compteRendu["motif"] = json_decode(getMotifById($data["id_motif"]));
			
			$compteRendu["echantillons"] = json_decode(getEchantillonByCompteRenduId($data["id"]));
			
			$compteRendu["praticien"] = json_decode(getPraticienById($data["id_praticien"]));
			
			$compteRendu["produit"] = json_decode(getProduitById($data["id_produit"]));
			
			$compteRendu["utilisateur"] = json_decode(getUtilisateurById($data["id_utilisateur"]));
		}
		
		return json_encode($compteRendu);
	}
	
	function getCompteRenduByIdProduit($idProduit)
	{
		include("connexionBdd.php");
		$comptesRendus = null;
		$i = 0;
		$req = $bdd->prepare("SELECT id_compte_rendu idCR FROM compte_rendu_produit WHERE id_produit = ?");
		$req->execute(array($idProduit));
		while($data = $req->fetch())
		{
			$comptesRendus[$i] = json_decode(getCompteRenduById($data["idCR"]));
			$i++;
		}
		
		return json_encode($comptesRendus);
	}
	
	function getCompteRenduByUtilisateurId($id)
	{
		include("connexionBdd.php");
		$comptesRendus = null;
		$i = 0;
		$req = $bdd->prepare("SELECT id FROM compte_rendu_visite WHERE id_utilisateur = ? ORDER BY date_creation DESC");
		$req->execute(array($id));
		while($data = $req->fetch())
		{
			$comptesRendus[$i] = json_decode(getCompteRenduById($data["id"]));
			$i++;
		}
		return json_encode($comptesRendus);
	}
	
	function getEchantillonByCompteRenduId($id)
	{
		include("connexionBdd.php");
		$echantillons = null;
		$i = 0;
		
		$req = $bdd->prepare("SELECT echantillon_id FROM compte_rendu_echantillon WHERE compte_rendu_id = ?");
		$req->execute(array($id));
		while($data = $req->fetch())
		{
			$req2 = $bdd->prepare("SELECT * FROM echantillon WHERE id = ?");
			$req2->execute(array($data["echantillon_id"]));
			while($data2 = $req2->fetch())
			{
				$echantillons[$i]["id"] = $data2["id"];
				$echantillons[$i]["qte"] = $data2["qte"];
				$echantillons[$i]["produit"] = json_decode(getProduitById($data2["id_produit"]));
			}
		}
		return json_encode($echantillons);
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
	
	function getRdvByIdVisiteur($id_visiteur)
	{
		include("connexionBdd.php");
		
		$rdv = null;
		$i = 0;
		
		$req = $bdd->prepare("SELECT * FROM rdv WHERE id_visiteur = ?");
		$req->execute(array($id_visiteur));
		while($data = $req->fetch())
		{
			$rdv[$i]["id"] = $data["id"];
			$rdv[$i]["date"] = $data["date"];
			$rdv[$i]["description"] = $data["description"];
			
			require_once("fonctions.php");
			$rdv[$i]["praticien"] = json_decode(getPraticienById($data["id_praticien"]));
			
			$rdv[$i]["visiteur"] = json_decode(getUtilisateurById($data["id_visiteur"]));
			$rdv[$i]["utilisateur"] = json_decode(getUtilisateurById($data["id_utilisateur"]));
			
			$rdv[$i]["lieu"] = json_decode(getLieuById($data["id_lieu"]));
			
			$i++;
		}
		
		return json_encode($rdv);
	}
?>