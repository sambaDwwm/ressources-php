<?php

function passwordHash($password)
{
	$code = strlen($password);
	$code = ($code * 4)*($code/3);
	$sel = strlen($password);
	$sel2 = strlen($code.$password);
	$hash1 = sha1($sel.$password.$sel2);
	$hash2 = md5($hash1.$sel2);
	$final = $hash1.$hash2;
	substr($final , 7, 8);
	$final = strtoupper($final);
	return $final;
}