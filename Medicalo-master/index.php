<?php
session_start();
require('model/modelConnectDb.php');
require('controller/homeController.php');
require('controller/clientSessionController.php');
require('controller/filterController.php');
require('controller/ficheMedecinController.php');
require('controller/accountController.php');


try {
	if (isset($_GET['action']))
	{
		if ($_GET['action'] == 'vlogin')
		{
			(isset($_SESSION['user'])?header('location: index.php'):loginView());
		}
		elseif ($_GET['action'] == 'login')
		{
			if (!isset($_SESSION['user']))
			{
				if (empty($_POST['email']) && empty($_POST['password']))
				{
					throw new Exception("erreur de connexion (input libre)", 1);
				}
				else
				{
					$email = htmlspecialchars($_POST['email']);
					$password = htmlspecialchars($_POST['password']);
					loginUser($email, $password);
				}
			}
			else
			{
				header('location: index.php');
			}
		}
		elseif ($_GET['action'] == 'vRegist')
		{
			(isset($_SESSION['user'])?header('location: index.php'):registView());
		}
		elseif ($_GET['action'] == 'regist')
		{
			if (!isset($_SESSION['user']))
			{
				if (empty($_POST['nom']) || empty($_POST['prenom']) || empty($_POST['numero'])
				|| empty($_POST['email']) || empty($_POST['password']) || empty($_POST['passwordC']))
				{
					throw new Exception("Erreur d'inscription", 1);
				}
				else
				{
					$nom = strtolower(htmlspecialchars($_POST['nom']));
					$prenom = strtolower(htmlspecialchars($_POST['prenom']));
					$num = htmlspecialchars($_POST['numero']);
					$email = htmlspecialchars($_POST['email']);
					$password = htmlspecialchars($_POST['password']);
					$passwordC = htmlspecialchars($_POST['passwordC']);
					//test
					$passTest = testPassword($password, $passwordC);
					$emailTest = testEmailExist($email);

					if ($passTest == true && $emailTest == false) {
						addUser($nom, $prenom, $num, $email, $password);
					}
					else{
						throw new Exception("Erreur d'inscription mauvais mot de passe", 1);
					}
				}
			}
			else
			{
				header('location: index.php');
			}
		}
		elseif ($_GET['action'] == 'disconnect')
		{
			(isset($_SESSION['user'])?disconnect():header('location: index.php'));
		}
		elseif ($_GET['action'] == 'listeMedecin')
		{
			$catMedecin = isset($_POST['catMedecin']) ? true : false;
			$catVille = isset($_POST['catVille']) ? true : false;
			if($catMedecin && $catVille)
			{
   				$valueMedecin = htmlspecialchars(htmlentities($_POST['catMedecin'], ENT_QUOTES, "UTF-8"));
   				$valueVille = htmlspecialchars(htmlentities($_POST['catVille'], ENT_QUOTES, "UTF-8"));
   				// verification si les valeurs sont bonnes contre faille sql
   				verifMedecin($valueMedecin);
   				verifVille($valueVille);
   				//effectuer la recherche sql afficher les medecins etc juste apres
   				searchDoctorByFilter($valueMedecin, $valueVille);
			}
			else
			{
  				header('location: index.php'); 
			}
		}
		elseif ($_GET['action'] == 'ficheMedecin')
		{
			if (!isset($_SESSION['user']))
			{
				needCreatAccountView();
			}
			else
			{
				if (@$_GET['idMedecin'] && is_numeric($_GET['idMedecin']))//test contre faille SQL
				{
					$idMedecin = $_GET['idMedecin'];
					$idMedecin = substr($idMedecin, 0, 6); //contre bufferOverFlow
					ficheMedecinView($idMedecin);
				}
				else
				{
					header('location: index.php');
				}
			}
			
		}
		elseif ($_GET['action'] == 'myAccount')
		{
			(isset($_SESSION['user'])?myAccountView():header('location: index.php'));
		}
		elseif ($_GET['action'] == 'editPassword')
		{
			(isset($_SESSION['user'])?myNewPasswordView():header('location: index.php'));
		}
		elseif ($_GET['action'] == 'editUser')
		{
			if (isset($_SESSION['user']) && isset($_POST['idUser'])
				&& isset($_POST['label']) && isset($_POST['data']))
			{
				$idUser = $_POST['idUser'];
				$label = htmlspecialchars($_POST['label']);
				$data = htmlspecialchars(htmlentities(strtolower($_POST['data'])));
				(is_numeric($idUser)?$idUser:header('location: index.php'));
				testIdUserIdSession($idUser); // test pour voir si le id envoyer par POST est = a id de la session creer(								toujours plus de sécurité!!;))
				testLabelPost($label); //test pour voir si le champs input existe bien, contre faille SQL
				dataReadyToUpdate($label, $data);
			}
			else
			{
				header('location: index.php');
			}
		}
		elseif ($_GET['action'] == 'newPswd')
		{
			if (isset($_SESSION['user']) && isset($_POST['newPswd']) && isset($_POST['confNewPswd']))
			{
				$password = $_POST['newPswd'];
				$confPassword = $_POST['confNewPswd'];
				$newPassTest = testPassword($password, $confPassword);
				if ($newPassTest == true)
				{
					updatePassword($password);
				}
				else
				{
					echo "les deux mots de passes ne sont pas identique"; // a modifier en pop up
				}
			}
			else
			{
				header('location: index.php');
			}
		}
		elseif ($_GET['action'] == 'reserver')
		{
			if (isset($_SESSION['user']) && isset($_POST['jourR1']) &&
			isset($_POST['horaireReservation1']) && isset($_POST['mois']) && 
			isset($_POST['annee']) && isset($_POST['idMedecin']))
			{
				$i = 1;
				while ($i <= 31)
				{
					if (@$_POST['horaireReservation'.$i.''] != 'Choisir l heure' && 
						@$_POST['horaireReservation'.$i.''] != 'Indisponible') // @ pour ne pas voir l'erreur si une personne mal veillante essaye de creer une erreur
					{
						$heure = $_POST['horaireReservation'.$i.''];
						$jour = $_POST['jourR'.$i.''];
						$mois = $_POST['mois'];
						$annee = $_POST['annee'];
						$idMedecin = $_POST['idMedecin'];
						testHoraire($heure, $jour, $mois, $annee, $idMedecin);
						echo $heure;
						echo $jour;
						echo $mois;
						echo $annee;
						echo $idMedecin;
						//si ok 
						//vue accepter
						die();
					}
					$i +=1;
				}
				if ($heure == NULL)
				{
					header('location: javascript:history.go(-1)');
				}
			}
			else
			{
				header('location: index.php');
			}
		}
	}
	else
	{
		homeView();
	}
}
catch (Exception $e)
{
	die('Erreur : '.$e->getMessage());
}