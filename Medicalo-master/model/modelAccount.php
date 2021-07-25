<?php

function getDataUserById($idUser, $mailUser)
{
	$db = dbConnect();

	$data = $db->prepare('SELECT C.id, C.nom, C.prenom, C.numero, C.email FROM client C WHERE id=:idC AND email=:email');
	$data->execute(array('idC' => $idUser, 'email' => $mailUser));
	$userData = $data->fetch();

	return $userData;
}

function updatePasswordInDb($idUser, $mailUser, $passwordHashed)
{
  $db = dbConnect();

  $up_sql = "UPDATE client C
            SET mdp = '$passwordHashed'
            WHERE C.id = '$idUser'
            AND C.email = '$mailUser' ";

  $result = $db->query($up_sql);
  return $result;
}

function updateDataUserDb($idUser, $email, $label, $data)
{
	$db = dbConnect();

	switch ($label)
  {
    case 'nom':
    $up_sql = "UPDATE client C
            SET nom = '$data'
            WHERE C.id = '$idUser'
            AND C.email = '$email' ";
      break;

    case 'prenom':
    $up_sql = "UPDATE client C
            SET prenom = '$data'
            WHERE C.id = '$idUser'
            AND C.email = '$email' ";
      break;

    case 'numero':
    $up_sql = "UPDATE client C
            SET numero = '$data'
            WHERE C.id = '$idUser'
            AND C.email = '$email' ";
      break;

    default:
      throw new Exception("erreur de modification", 1);
      break;
    }
    $result = $db->query($up_sql);
   return $result;
}


