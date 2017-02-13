<?php
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
			
			require_once("getLieu.php");
			$serviceComptable["lieu"] = json_decode(getLieuById($data["id_lieu"]));
		}
		
		return json_encode($serviceComptable);
	}
?>