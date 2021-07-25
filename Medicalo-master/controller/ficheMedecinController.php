<?php
require('model/modelFicheMedecin.php');
require('algo/calendrier.php');

function needCreatAccountView()
{
	require('view/headerView.php');
	require('view/creerCompteView.php');
}

function ficheMedecinView($idMedecin)
{
	$dataM = getMedecinByIdDb($idMedecin);
	$idMedecinP = $dataM['id'];

	require('view/headerView.php');
	require('view/ficheMedecinView.php');
}

function testHoraire($heure, $jour, $mois, $annee, $idMedecin)
{
	$allHeure = ['10h00 - 10h30', '10h30 - 11h00', '11h30 - 12h00', '13h00 - 13h30', '13h30 - 14h00', '14h30 - 15h00', '15h00 - 15h30', '15h30 - 16h00', '12h00 - 12h30'];

	if (in_array($heure, $allHeure) && is_numeric($jour) 
		&& is_numeric($mois) && is_numeric($idMedecin)
		&& is_numeric($annee))
	{
		return true;
	}
	else{
		header('location: index.php');
	}
}
