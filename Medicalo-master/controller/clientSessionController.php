<?php
require('model/model_clientSession.php');
require('algo/moulin.php');

function loginView()
{
	require('view/headerView.php');
	require('view/loginView.php');
}

function registView()
{
	require('view/headerView.php');
	require('view/registView.php');
}

function testPassword($password1, $password2)
{
	if ($password1 == $password2) {
		return true;
	}
	else
	{
		return false;
	}
}

function testEmailExist($email)
{
	$existeEmail = testEmailDb($email);
	return (($existeEmail) ? true : false);
}

function addUser($nom, $prenom, $num, $email, $password)
{
	$nom = htmlentities(strip_tags($nom));
	$prenom = htmlentities(strip_tags($prenom));
	$num = substr($num, 0, 10);
	$email = htmlspecialchars($email);
	$passwordHashed = passwordHash($password);

	$answDb = addUserDb($nom, $prenom, $num, $email, $passwordHashed);

	if ($answDb == true)
	{
		header('location: index.php?action=vlogin');
	}
	else
	{
		throw new Exception("Erreur d'inscription db", 1);
	}
}

function loginUser($email, $password)
{
	$email = htmlentities($email);
	$password = htmlentities($password);
	$passwordHashed = passwordHash($password);

	$answDb = getUserDb($email, $passwordHashed);
	if ($answDb == false) {
		echo "mauvais mot de passe ou login !";
	}
	else
	{
		$id = $answDb['id'];
		$nom = $answDb['nom'];
		$email = $answDb['email'];
		
		$_SESSION['user'] = array('id' => $id, 'userName' => $nom, 'email' => $email );

		header('location: index.php');
	}
}

function disconnect()
{
	session_destroy();
	header('location: index.php');
}