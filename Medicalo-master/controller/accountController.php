<?php
require('model/modelAccount.php');

function myAccountView()
{
	$idUser = $_SESSION['user']['id'];
	$mailUser = $_SESSION['user']['email'];

	$dataUser = getDataUserById($idUser, $mailUser); //plus sécurisé entre autre
	//requette reservation etc...

	require('view/headerView.php');
	require('view/myAccountView.php');
}

function myNewPasswordView()
{
	require('view/headerView.php');
	require('view/myNewPasswordView.php');
}

function updatePassword($password)
{
	$idUser = $_SESSION['user']['id'];
	$mailUser = $_SESSION['user']['email'];

	$passwordHashed = passwordHash($password);
	$answDb = updatePasswordInDb($idUser, $mailUser, $passwordHashed);

	header('location: index.php?action=myAccount');
}

function testIdUserIdSession($idUser) 
{
	$idUserSession = $_SESSION['user']['id'];
	($idUserSession == $idUser?$idUser:header('location: index.php'));
}

function testLabelPost($label)
{
	$allLabel = array('nom', 'prenom', 'numero');
	(in_array($label, $allLabel)?$label:header('location: index.php'));
}

function dataReadyToUpdate($label, $data)
{
	$idUser = $_SESSION['user']['id'];
	$email = $_SESSION['user']['email'];

	$answDb = updateDataUserDb($idUser, $email, $label, $data);

	header('location: index.php?action=myAccount');
}

