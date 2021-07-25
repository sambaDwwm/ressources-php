<?php
function dbconnect()
{
	$host = "127.0.0.1";
	$dbname = "dbmedicalo" ;
	$user = "root";
	$pswd = "";
	try
	{
	    $db = new PDO('mysql:host='.$host.';port=3306;dbname='.$dbname.';charset=utf8', $user, $pswd , array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	    return $db;
	}
	catch(Exception $e)
	{
	    die('Erreur : '.$e->getMessage());
	}
}
