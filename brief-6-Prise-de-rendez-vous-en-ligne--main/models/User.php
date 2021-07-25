<?php

class User{
//check login    
    static public function login($data){
        $reference = $data["reference"];
        try {
            $query = "SELECT * FROM user WHERE reference = :reference";
            $stmt = DB::connect()->prepare($query);
            $stmt->execute(array(":reference"=>$reference));
            $user = $stmt->fetch(PDO::FETCH_OBJ);
            return $user;
        } catch (PDOException $ex) {
            echo "error : ".$ex;
        }
    }

	
//creat users
    static public function createUser($data){
        $query = 'INSERT INTO user (reference,nom,prenom,age,email,tel)
                     VALUES (:reference,:nom,:prenom,:age,:email,:tel)';
        $stmt = DB::connect()->prepare($query);
        $stmt->bindParam(':reference',$data['reference']);
        $stmt->bindParam(':nom',$data['nom']);
        $stmt->bindParam(':prenom',$data['prenom']);
        $stmt->bindParam(':age',$data['age']);
        $stmt->bindParam(':email',$data['email']);
        $stmt->bindParam(':tel',$data['tel']);
        if($stmt->execute()){
            return 'ok';
        }else{
            return 'error';
        }
        // $stmt->close();
        $stmt = null;
    }
}