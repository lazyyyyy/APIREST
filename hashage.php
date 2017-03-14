<?php
	/*function hashage($mot)
	{
		$hash = md5($mot);
		
		return json_encode($hash);
	}*/
	require_once("fonctions.php");
	
	echo hashage($_POST["mdp"]);
?>