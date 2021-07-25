<?php

function searchDoctorDb($valueMedecin, $valueVille)
{
	$db = dbConnect();

	$data = $db->prepare('SELECT M.id, M.nom, M.prenom, M.nomcabinet, M.adresse, M.codepostal, M.ville, M.categorie FROM medecin M WHERE M.categorie = :cat AND M.ville = :ville');
	$data->execute(array('cat' => $valueMedecin, 'ville' => $valueVille));
	return $data;
}

