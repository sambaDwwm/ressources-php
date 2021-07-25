<?php
require('model/modelMedecin.php');

function verifMedecin($valueM)
{
	$valueM = strip_tags($valueM);
	$allCatMedecin = array('dermatologue', 'generaliste', 'dentiste');
	if (in_array($valueM, $allCatMedecin))
	{
		return true;
	}
	else
	{
		header('location: index.php');
	}
}

function verifVille($valueV)
{
	$valueV = strip_tags($valueV);
	$allCity = array('paris', 'bordeaux', 'lyon', 'marseille', 'toulouse', 'rennes');
	if (in_array($valueV, $allCity))
	{
		return true;
	}
	else
	{
		header('location: index.php');
	}
}

function searchDoctorByFilter($valueMedecin, $valueVille)
{
	$dataDoctor = searchDoctorDb($valueMedecin, $valueVille);

	require('view/headerView.php');
	require('view/listeMedecinView.php');
}

