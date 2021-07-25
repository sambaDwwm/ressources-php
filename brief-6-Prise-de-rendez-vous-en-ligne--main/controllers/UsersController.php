<?php

class UsersController{
    public function auth(){
        if(isset($_POST["submit"])){
            $data["reference"] = $_POST["reference"];
            $result = User::login($data);  //send to model
            if($result->reference === $_POST["reference"] ){  
                $_SESSION["logged"] = true;
                $_SESSION["reference"] = $result->reference;
                // $_SESSION["matiere"] = $result->matiere;
                $_SESSION["admin"] = $result->admin;
                Redirect::to("dashboard");
            }else{
                Session::set("error","reference est incorrect");
                Redirect::to("login");
            }
        }
    }

	///here
    public function register(){
       
        $data = array(
            "reference" => $_POST["reference"],
            "nom" => $_POST["nom"],
            "prenom" => $_POST["prenom"],
            "age" => $_POST["age"],
            "email" => $_POST["email"],
            "tel" => $_POST["tel"],
        );
        $result = User::createUser($data); //send to model
        if($result === "ok"){
            Session::set("success","Compte crée");
            $_SESSION["logged"] = true;
            $_SESSION["reference"] = $_POST["reference"];
            
            Redirect::to("dashboard");
        }else{
            echo $result;
        }
    }
    public static function logout(){
        session_destroy();
    }

    
    // public function getAllUsers(){
	// 	$AllUser = TheClass::getUsers();

	// 	return $AllUser;
	// }
}