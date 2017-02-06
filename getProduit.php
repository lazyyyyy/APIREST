<?php
	function getProduit($nom)
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
			
			$req2 = $bdd->prepare("SELECT * FROM type_individu WHERE id = ?");
			$req2->execute(array($data["id_type_individu"]));
			if($data2 = $req2->fetch())
			{
				$produit[$i]["type_individu"]["id"] = $data2["id"];
				$produit[$i]["type_individu"]["libelle"] = $data2["libelle"];
				$produit[$i]["type_individu"]["description"] = $data2["description"];
			}
			
			$req2 = $bdd->prepare("SELECT * FROM composant WHERE id = ?");
			$req2->execute(array($data["id_composant"]));
			if($data2 = $req2->fetch())
			{
				$produit[$i]["composant"]["id"] = $data2["id"];
				$produit[$i]["composant"]["libelle"] = $data2["libelle"];
				$produit[$i]["composant"]["description"] = $data2["description"];
			}
			
			$req2 = $bdd->prepare("SELECT * FROM laboratoire WHERE id = ?");
			$req2->execute(array($data["id_laboratoire"]));
			if($data2 = $req2->fetch())
			{
				$produit[$i]["laboratoire"]["id"] = $data2["id"];
				$produit[$i]["laboratoire"]["nom"] = $data2["nom"];
				
				$req3 = $bdd->prepare("SELECT * FROM lieu WHERE id = ?");
				$req3->execute(array($data2["id_lieu"]));
				if($data3 = $req3->fetch())
				{
					$produit[$i]["laboratoire"]["lieu"]["id"] = $data3["id"];
					$produit[$i]["laboratoire"]["lieu"]["libelle"] = $data3["libelle"];
					$produit[$i]["laboratoire"]["lieu"]["adresse"] = $data3["adresse"];
					$produit[$i]["laboratoire"]["lieu"]["cp"] = $data3["cp"];
					$produit[$i]["laboratoire"]["lieu"]["ville"] = $data3["ville"];
					$produit[$i]["laboratoire"]["lieu"]["pays"] = $data3["pays"];
					
					$req4 = $bdd->prepare("SELECT * FROM region WHERE id = ?");
					$req4->execute(array($data3["region_id"]));
					if($data4 = $req4->fetch())
					{
						$produit[$i]["laboratoire"]["lieu"]["region"]["id"] = $data4["id"];
						$produit[$i]["laboratoire"]["lieu"]["region"]["libelle"] = $data4["libelle"];
					}
				}
			}
			
			$req2 = $bdd->prepare("SELECT * FROM dosage WHERE id = ?");
			$req2->execute(array($data["id_dosage"]));
			if($data2 = $req2->fetch())
			{
				$produit[$i]["dosage"]["id"] = $data2["id"];
				$produit[$i]["dosage"]["quantite"] = $data2["quantite"];
				$produit[$i]["dosage"]["unite"] = $data2["unite"];
				$produit[$i]["dosage"]["description"] = $data2["description"];
			}
			
			$req2 = $bdd->prepare("SELECT * FROM famille_produit WHERE id = ?");
			$req2->execute(array($data["id_famille_produit"]));
			if($data2 = $req2->fetch())
			{
				$produit[$i]["famille_produit"]["id"] = $data2["id"];
				$produit[$i]["famille_produit"]["libelle"] = $data2["libelle"];
				$produit[$i]["famille_produit"]["description"] = $data2["description"];
			}
			
			$i++;
		}
		
		return json_encode($produit);
	}
	
	/*$produit = json_decode(getProduit("es"));
	var_dump($produit);*/
?>