<?php

function addUserDb($nom, $prenom, $num, $email, $passHashed)
{
	$db = dbConnect();

	$data = $db->prepare('INSERT INTO client (nom, prenom, numero, email, mdp) VALUES (:nom, :pre, :num, :email, :mdp)');
	$addData = $data->execute(array('nom' => $nom, 'pre' => $prenom, 'num' => $num, 'email' => $email, 
		'mdp' => $passHashed));
	return $addData;
}

function testEmailDb($email)
{
	$db = dbConnect();

	$data = $db->prepare('SELECT email FROM client WHERE email=:email');
	$data->execute(array('email' => $email ));
	$existeEmail = $data->fetch();
	return $existeEmail;
}

function getUserDb($email, $passwordHashed)
{
	$db = dbConnect();

	$data = $db->prepare('SELECT id, nom, email FROM client WHERE email=:email AND mdp=:mdp');
	$data->execute(array('email' => $email, 'mdp' => $passwordHashed));
	$userData = $data->fetch();

	return $userData;
}
