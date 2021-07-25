<?php

function getMedecinByIdDb($idMedecin)
{
	$db = dbConnect();

	$data = $db->prepare('SELECT M.id, M.nom, M.prenom, M.nomcabinet, M.adresse, M.codepostal, M.ville, M.categorie FROM medecin M WHERE M.id = :idM');
	$data->execute(array('idM' => $idMedecin));
	$dataDoctorOnly = $data->fetch();
	return $dataDoctorOnly;
}
