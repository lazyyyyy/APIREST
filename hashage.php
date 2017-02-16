<?php
	function hashage($mot)
	{
		include("hashKey.php");
		$hash = crypt($mot, $key);
		
		return json_encode($hash);
	}
	
	echo hashage($_POST["mdp"]);
?>