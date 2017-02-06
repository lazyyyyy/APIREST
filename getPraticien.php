<?php
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
			
			$req2 = $bdd->prepare("SELECT * FROM fonction_praticien WHERE id = ?");
			$req2->execute(array($data["id_fonction_praticien"]));
			if($data2 = $req2->fetch())
			{
				$praticiens[$i]["fonction"]["id"] = $data2["id"];
				$praticiens[$i]["fonction"]["libelle"] = $data2["libelle"];
				$praticiens[$i]["fonction"]["description"] = $data2["description"];
			}
			
			$req2 = $bdd->prepare("SELECT * FROM type_praticien WHERE id = ?");
			$req2->execute(array($data["id_type_praticien"]));
			if($data2 = $req2->fetch())
			{
				$praticiens[$i]["type"]["id"] = $data2["id"];
				$praticiens[$i]["type"]["libelle"] = $data2["libelle"];
				$praticiens[$i]["type"]["description"] = $data2["description"];
			}
			
			$req2 = $bdd->prepare("SELECT * FROM specialite WHERE id = ?");
			$req2->execute(array($data["id_specialite"]));
			if($data2 = $req2->fetch())
			{
				$praticiens[$i]["specialite"]["id"] = $data2["id"];
				$praticiens[$i]["specialite"]["libelle"] = $data2["libelle"];
				$praticiens[$i]["specialite"]["description"] = $data2["description"];
			}
			
			$req2 = $bdd->prepare("SELECT * FROM lieu WHERE id = ?");
			$req2->execute(array($data["id_lieu"]));
			if($data2 = $req2->fetch())
			{
				$praticiens[$i]["lieu"]["id"] = $data2["id"];
				$praticiens[$i]["lieu"]["libelle"] = $data2["libelle"];
				$praticiens[$i]["lieu"]["adresse"] = $data2["adresse"];
				$praticiens[$i]["lieu"]["cp"] = $data2["cp"];
				$praticiens[$i]["lieu"]["ville"] = $data2["ville"];
				$praticiens[$i]["lieu"]["pays"] = $data2["pays"];
				
				$req3 = $bdd->prepare("SELECT * FROM region WHERE id = ?");
				$req3->execute(array($data2["region_id"]));
				if($data3 = $req3->fetch())
				{
					$praticiens[$i]["lieu"]["region"]["id"] = $data3["id"];
					$praticiens[$i]["lieu"]["region"]["libelle"] = $data3["libelle"];
				}
			}
			
			$i++;
		}
		return json_encode($praticiens);
		
	}
	
	/*$praticiens = json_decode(getPraticienByName(""));
	var_dump($praticiens);*/
	
?>