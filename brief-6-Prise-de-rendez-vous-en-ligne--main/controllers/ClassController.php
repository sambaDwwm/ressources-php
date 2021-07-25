<?php 

class ClassController{
// done
	public function getAllClass(){
		$AllClass = TheClass::getAll();

		return $AllClass;
	}
	
	public function getOneClasse(){
		$ses=$_SESSION['reference'];
			$data = array(
				'reference' => "$ses"
			);
			$classe = TheClass::getClasse($data);
			return $classe;
	}

	public function getOne(){
		$ses=$_POST['id'];
			$data = array(
				'id' => "$ses"
			);
			$classe = TheClass::getOne($data);
			return $classe;
	}

	
// done
	public function addClasse(){
		if(isset($_POST['submit'])){
			$data = array(
				'classname' => $_POST['classname'],
				'groupnumber' => $_POST['groupnumber'],
				'statut' => $_POST['statut'],
			);
			$result = TheClass::add($data);
			if($result === 'ok'){
				Session::set('success','Employé Ajouté');
				Redirect::to('dashboard');
			}else{
				echo $result;
			}
		}
	}

	public function updateClasse(){
		if(isset($_POST['submit'])){
			$data = array(
				'id' => $_POST['id'],
				'date' => $_POST['date'],
				'note' => $_POST['note'],
				'horaire' => $_POST['horaire'],
			);
			$result = TheClass::update($data);
			if($result === 'ok'){
				Session::set('success','Employé Modifié');
				Redirect::to('dashboard');
			}else{
				echo $result;
			}
		}
	}
	public function deleteClasse(){
		if(isset($_POST['id'])){
			$data['id'] = $_POST['id'];
			$result = TheClass::delete($data);
			if($result === 'ok'){
				Session::set('success','Employé Supprimé');
				Redirect::to('dashboard');
			}else{
				echo $result;
			}
		}
	}

	// public function getAllTimes(){
	// 	$AllTimes = TheClass::getAllTime();

	// 	return $AllTimes;
	// }
	


	public function reservClasse(){
		// if(isset($_POST['submit'])){
			$data = array(
				'date' => $_POST['date'],
				'note' => $_POST['note'],
				'horaire' => $_POST['horaire'],
				'reference' => $_POST['reference'],
			);
			$result = TheClass::addReserv($data);
			if($result === 'ok'){
				Session::set('success','reserved');
				Redirect::to('dashboard');
			}else{
				echo $result;
			}
		// }
	}

	
	// public function reservClasse(){
		
	// 	$AllClass = TheClass::checkReserve($_POST['date'],$_POST['note'],
	// 	$_POST['horaire'],
	// 	$_POST['id']);
	// 	if ($AllClass == true){
	// 		echo '<div class="alert alert-danger">already booked</div>';
	// 		// echo'dskfsdf';
	// 	}else if($AllClass == false){
	// 		$data = array(
	// 			'date' => $_POST['date'],
	// 			'note' => $_POST['note'],
	// 			'horaire' => $_POST['horaire'],
	// 			'id' => $_POST['id'],
	// 		);
	// 		$result = TheClass::addReserv($data);
	// 		if($result === 'ok'){
	// 			echo '<div class="alert alert-success justify-content-end">success</div>';
				
	// 			Redirect::to('home');
	// 		}else{
	// 			echo $result;
	// 		}
			
	// 	}
	// 	return $AllClass;
	// }

}



?>