<?php
	$mdp = "te";
	
	include("hashKey.php");
	$hash = crypt($mdp, $key);
	
	if(hash_equals($hash, crypt("te", $key)))
	{
		echo "mot de passe correct";
	}
	else
	{
		echo "incorrect";
	}
?>